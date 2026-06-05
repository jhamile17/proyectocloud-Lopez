<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /** Límite por ítem y límite global del pedido */
    private const MAX_QTY_PER_ITEM = 8;
    private const MAX_QTY_TOTAL    = 8;

    /**
     * Estructura de sesión:
     * cart = [
     *   'items' => [ rowId => ['rowId','id','name','price','qty','image','url','variant'] ],
     *   'count' => (int) suma de qty,
     *   'total' => (float) total general
     * ]
     */

    /** Obtener carrito desde sesión o estructura por defecto */
    private function getCart(): array
    {
        return session()->get('cart', [
            'items' => [],
            'count' => 0,
            'total' => 0.0,
        ]);
    }

    /** Suma total de unidades del carrito */
    private function totalUnits(array $cart): int
    {
        $sum = 0;
        foreach (($cart['items'] ?? []) as $it) {
            $sum += (int)($it['qty'] ?? 1);
        }
        return $sum;
    }

    /**
     * Guardar carrito en sesión recalculando count y total (con saneo por ítem)
     * (El límite global se controla en add/update)
     */
    private function putCart(array $cart): array
    {
        $items = $cart['items'] ?? [];

        $count = 0;
        $total = 0.0;

        foreach ($items as $rowId => &$it) {
            $qty   = (int) ($it['qty'] ?? 1);
            $price = (float) ($it['price'] ?? 0);

            // Forzar rangos válidos por ítem
            $qty   = max(1, min(self::MAX_QTY_PER_ITEM, $qty));
            $price = max(0, $price);

            $it['qty']   = $qty;
            $it['price'] = $price;

            $count += $qty;
            $total += $price * $qty;
        }
        unset($it);

        $cart['items'] = $items;
        $cart['count'] = $count;
        $cart['total'] = round($total, 2);

        session()->put('cart', $cart);

        return $cart;
    }

    /** Generar rowId estable por producto + variación */
    private function makeRowId($id, $variant = null): string
    {
        if (is_array($variant)) {
            $variantKey = json_encode($variant, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            $variantKey = (string) ($variant ?? '');
        }
        return 'p_' . substr(sha1($id . '|' . $variantKey), 0, 12);
    }

    /** GET /cart */
    public function index()
    {
        $cart = $this->putCart($this->getCart());
        return response()->json($cart);
    }

    /**
     * POST /cart/add
     * Body JSON:
     * id (req), name (req), price (req >=0), qty (>=1), image, url, variant
     */
    public function add(Request $request)
    {
        $data = $request->validate([
            'id'      => ['required'],
            'name'    => ['required', 'string', 'max:255'],
            'price'   => ['required', 'numeric', 'min:0'],
            'qty'     => ['nullable', 'integer', 'min:1'],
            'image'   => ['nullable', 'string'],
            'url'     => ['nullable', 'string'],
            'variant' => ['nullable'], // string|array
        ]);

        $cart = $this->getCart();

        // Petición del usuario (clamp 1..MAX_PER_ITEM)
        $requestedQty = (int) ($data['qty'] ?? 1);
        $requestedQty = max(1, min(self::MAX_QTY_PER_ITEM, $requestedQty));

        // Cuántos “cupo” quedan considerando el límite global
        $currentTotal = $this->totalUnits($cart);
        $slotsLeft    = self::MAX_QTY_TOTAL - $currentTotal;

        if ($slotsLeft <= 0) {
            // Sin cupo: devuelve carrito sin cambios
            $cart = $this->putCart($cart);
            return response()->json($cart + ['warning' => 'Máximo 8 unidades por pedido.']);
        }

        // No permitir agregar más de lo que queda disponible
        $qtyToAdd = min($requestedQty, $slotsLeft);

        $variant = $data['variant'] ?? null;
        $rowId   = $this->makeRowId($data['id'], $variant);

        if (isset($cart['items'][$rowId])) {
            $newQty = (int)$cart['items'][$rowId]['qty'] + $qtyToAdd;
            // Límite por línea
            $newQty = min(self::MAX_QTY_PER_ITEM, $newQty);

            // Además, respeta el global: recalcula cupo considerando que esta línea es la afectada
            $totalWithoutThis = $currentTotal - (int)$cart['items'][$rowId]['qty'];
            $maxForThisLine   = min(self::MAX_QTY_PER_ITEM, self::MAX_QTY_TOTAL - $totalWithoutThis);
            $newQty           = min($newQty, max(1, $maxForThisLine));

            $cart['items'][$rowId]['qty'] = $newQty;

        } else {
            // Si línea nueva pide más que el cupo, recortamos
            $qtyToAdd = min($qtyToAdd, self::MAX_QTY_PER_ITEM);

            $cart['items'][$rowId] = [
                'rowId'   => $rowId,
                'id'      => $data['id'],
                'name'    => $data['name'],
                'price'   => (float) $data['price'],
                'qty'     => $qtyToAdd,
                'image'   => $data['image'] ?? null,
                'url'     => $data['url'] ?? null,
                'variant' => $variant,
            ];
        }

        $cart = $this->putCart($cart);
        return response()->json($cart);
    }

    /**
     * PATCH /cart/{rowId}
     * Body JSON: { qty: int >= 1 }
     */
    public function update(Request $request, string $rowId)
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->getCart();

        if (!isset($cart['items'][$rowId])) {
            $cart = $this->putCart($cart);
            return response()->json($cart);
        }

        // Límite por línea 1..8
        $desired = max(1, min(self::MAX_QTY_PER_ITEM, (int)$data['qty']));

        // Respeta el límite global 8 considerando el resto de líneas
        $totalWithoutThis = 0;
        foreach ($cart['items'] as $key => $it) {
            if ($key === $rowId) continue;
            $totalWithoutThis += (int)($it['qty'] ?? 1);
        }
        $maxForThisLine = min(self::MAX_QTY_PER_ITEM, self::MAX_QTY_TOTAL - $totalWithoutThis);
        $maxForThisLine = max(1, $maxForThisLine);

        $cart['items'][$rowId]['qty'] = min($desired, $maxForThisLine);

        $cart = $this->putCart($cart);
        return response()->json($cart);
    }

    /** DELETE /cart/{rowId} */
    public function remove(string $rowId)
    {
        $cart = $this->getCart();

        if (isset($cart['items'][$rowId])) {
            unset($cart['items'][$rowId]);
            $cart = $this->putCart($cart);
        } else {
            $cart = $this->putCart($cart);
        }

        return response()->json($cart);
    }

    /** DELETE /cart */
    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'items' => [],
            'count' => 0,
            'total' => 0.0,
        ]);
    }
}
