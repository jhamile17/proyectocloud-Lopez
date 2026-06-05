<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        // Detectar campo de total en orders
        $orderTotalField = Schema::hasColumn('orders','total_price') ? 'total_price'
            : (Schema::hasColumn('orders','total') ? 'total' : null);

        if (!$orderTotalField) {
            $this->command->warn('No existe columna total/total_price en orders. Saliendo.');
            return;
        }

        // Detectar columnas clave en order_items
        $priceCol = Schema::hasColumn('order_items','unit_price') ? 'unit_price'
            : (Schema::hasColumn('order_items','price') ? 'price' : null);

        $oiProdFk = Schema::hasColumn('order_items','product_id') ? 'product_id'
            : (Schema::hasColumn('order_items','products_id') ? 'products_id' : null);

        $oiHasSubtotal = Schema::hasColumn('order_items', 'subtotal');

        if (!$priceCol)  { $this->command->warn('No existe price/unit_price en order_items.'); }
        if (!$oiProdFk)  { $this->command->warn('No existe FK product_id/products_id en order_items.'); }

        // Detectar FK de shipping en orders y PK en shipping_addresses
        $orderShipFk = $this->pickColumn('orders', ['shipping_address_id','shipping_id','address_id']);
        $shipPk      = $this->pickColumn('shipping_addresses', ['id','shipping_address_id','address_id']);

        // Usuarios (preferir customers)
        $hasIsAdmin = Schema::hasColumn('users','is_admin');
        $query = DB::table('users');
        if ($hasIsAdmin) {
            $query->where(function($q){
                $q->where('role','customer')->orWhere('is_admin', false);
            });
        } else {
            if (Schema::hasColumn('users','role')) {
                $query->where('role','customer');
            }
        }
        $userIds = $query->pluck('id')->all();
        if (empty($userIds)) { // fallback a todos los usuarios
            $userIds = DB::table('users')->pluck('id')->all();
        }
        if (empty($userIds)) {
            $this->command->warn('No hay usuarios. Saliendo.');
            return;
        }

        // Productos
        $prodPk = Schema::hasColumn('products','product_id') ? 'product_id' : 'id';
        $products = DB::table('products')->select($prodPk.' as id','price','name')->get()->all();
        if (empty($products)) {
            $this->command->warn('No hay productos para crear órdenes.');
            return;
        }

        // Configuración general
        $now = Carbon::now()->startOfDay();
        $ordersToCreate = 80;
        $addressCache = [];

        for ($n = 0; $n < $ordersToCreate; $n++) {
            // Usuario
            $uid = Arr::random($userIds);

            // Fecha de creación aleatoria
            $monthOffset = random_int(0, 11);
            $day = random_int(1, 25);
            $created = $now->copy()->subMonths($monthOffset)->startOfMonth()
                ->addDays($day-1)->setTime(random_int(8, 21), random_int(0,59));

            // Estado de la orden según el tipo de columna
            $paid = mt_rand(1,100) <= 70;
            $orderStatusType = $this->columnType('orders', 'status');
            $status = $this->coerceOrderStatusValue($orderStatusType, $paid);

            // Dirección de envío si aplica
            $shipId = null;
            if ($orderShipFk && $shipPk && Schema::hasTable('shipping_addresses')) {
                if (!isset($addressCache[$uid])) {
                    $addressCache[$uid] = $this->ensureShippingAddressForUser($uid, $shipPk, $created);
                }
                $shipId = $addressCache[$uid];
            }

            // Insert seguro de la orden
            $hasSubtotal = Schema::hasColumn('orders','subtotal');
            $hasTax      = Schema::hasColumn('orders','tax');

            $orderData = [
                'user_id'          => $uid,
                'status'           => $status,
                $orderTotalField   => 0,
                'created_at'       => $created,
                'updated_at'       => $created,
            ];
            if ($orderShipFk && $shipId) $orderData[$orderShipFk] = $shipId;
            if ($hasSubtotal) $orderData['subtotal'] = 0;
            if ($hasTax)      $orderData['tax']      = 0;

            $orderId = DB::table('orders')->insertGetId($orderData);

            // Items
            $subtotal = 0;
            if ($oiProdFk && $priceCol) {
                $itemsCount = random_int(1, 3);
                for ($i = 0; $i < $itemsCount; $i++) {
                    $p = Arr::random($products);
                    $qty  = random_int(1, 3);
                    $unit = (float) $p->price;
                    $line = $unit * $qty;
                    $subtotal += $line;

                    $itemData = [
                        'order_id'   => $orderId,
                        $oiProdFk    => $p->id,
                        'quantity'   => $qty,
                        $priceCol    => $unit,
                        'created_at' => $created,
                        'updated_at' => $created,
                    ];
                    if ($oiHasSubtotal) $itemData['subtotal'] = $line;

                    DB::table('order_items')->insert($itemData);
                }
            } else {
                $this->command->warn('Saltando items: faltan columnas en order_items (FK producto o precio).');
            }

            // IGV y total
            $tax   = round($subtotal * 0.18, 2);
            $total = round($subtotal + $tax, 2);

            $updateData = [
                $orderTotalField => $total,
                'updated_at'     => $created,
            ];
            if ($hasSubtotal) $updateData['subtotal'] = $subtotal;
            if ($hasTax)      $updateData['tax']      = $tax;

            DB::table('orders')->where('id', $orderId)->update($updateData);

            // Pago dinámico si está pagado
            if ($paid) {
                $this->insertPaymentDynamic($orderId, $total, $created);
            }
        }
    }

    private function pickColumn(string $table, array $candidates): ?string
    {
        if (!Schema::hasTable($table)) return null;
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) return $c;
        }
        return null;
    }

    private function ensureShippingAddressForUser(int $userId, string $shipPk, Carbon $created): ?int
    {
        if (!Schema::hasTable('shipping_addresses')) return null;

        $existing = DB::table('shipping_addresses')->where('user_id', $userId)->first();
        if ($existing && isset($existing->{$shipPk})) return (int)($existing->{$shipPk});

        $cols = Schema::getColumnListing('shipping_addresses');

        // Valores por defecto (Perú)
        $defaults = [
            'full_name'   => 'Cliente '.$userId,
            'name'        => 'Cliente '.$userId,
            'address'     => 'Av. Demo 123',
            'line1'       => 'Av. Demo 123',
            'line2'       => 'Depto 101',
            'district'    => 'Miraflores',
            'province'    => 'Lima',
            'department'  => 'Lima',
            'state'       => 'Lima',
            'region'      => 'Lima',
            'city'        => 'Lima',
            'ubigeo'      => '150122',
            'postal_code' => '15074',
            'zip'         => '15074',
            'zipcode'     => '15074',
            'zip_code'    => '15074',
            'country'     => 'PE',
            'phone'       => '999999999',
            'reference'   => 'Referencia demo',
            'doc_type'    => 'DNI',
            'doc_number'  => '12345678',
        ];

        $row = [
            'user_id'    => $userId,
            'created_at' => $created,
            'updated_at' => $created,
        ];

        foreach ($defaults as $col => $val) {
            if (in_array($col, $cols, true)) {
                $row[$col] = $val;
            }
        }

        return (int) DB::table('shipping_addresses')->insertGetId($row);
    }

    /**
     * Inserta un pago usando SOLO las columnas existentes en "payments".
     * Sanea 'status' por tipo real (ENUM/INT/VARCHAR/CHAR) y reintenta si MySQL 1265.
     */
    private function insertPaymentDynamic(int $orderId, float $amount, Carbon $created): void
    {
        if (!Schema::hasTable('payments')) return;

        $cols = Schema::getColumnListing('payments');

        // Detectar FK a orders en payments
        $payOrderFk = null;
        foreach (['order_id','orders_id','sale_id','orderId'] as $c) {
            if (in_array($c, $cols, true)) { $payOrderFk = $c; break; }
        }
        if (!$payOrderFk) return;

        // Tipo real de status (si existe)
        $statusVal = null;
        $statusType = null;
        if (in_array('status', $cols, true)) {
            $statusType = $this->columnType('payments', 'status'); // enum(...)/tinyint(1)/varchar(n)/char(n)
            $statusVal  = $this->coerceStatusValue($statusType);
        }

        // Valores por defecto (solo si las columnas existen)
        $defaults = [
            $payOrderFk     => $orderId,
            'amount'        => $amount,
            'transaction_id'=> 'TX'.str_pad((string)random_int(1, 9999999), 7, '0', STR_PAD_LEFT),
            'paid_at'       => $created->copy()->addMinutes(random_int(1, 120)),
            'created_at'    => $created,
            'updated_at'    => $created,
            // Opcionales
            'currency'      => 'PEN',
            'method'        => Arr::random(['Yape','Plin','Tarjeta','Efectivo']),
            'gateway'       => 'manual',
            'channel'       => 'pos',
            'provider'      => 'local',
            'reference'     => 'REF'.str_pad((string)random_int(1, 9999999), 7, '0', STR_PAD_LEFT),
            'description'   => 'Pago de pedido #'.$orderId,
        ];
        if ($statusVal !== null) $defaults['status'] = $statusVal;

        // Construir fila SOLO con columnas existentes
        $row = [];
        foreach ($defaults as $col => $val) {
            if (in_array($col, $cols, true)) $row[$col] = $val;
        }

        // Fallback si no existiera 'amount'
        if (!isset($row['amount']) && in_array('total', $cols, true)) {
            $row['total'] = $amount;
        }

        // Saneo final del status
        if (array_key_exists('status', $row)) {
            $row['status'] = $this->sanitizeColumnValue('payments', 'status', $row['status'], $statusType);
        }

        // Insert con reintento si hay 1265 (Data truncated)
        try {
            DB::table('payments')->insert($row);
        } catch (QueryException $e) {
            $msg = $e->getMessage();
            if (strpos($msg, 'Data truncated for column \'status\'') !== false || strpos($msg, '1265') !== false) {
                // Reintento forzando a un valor numérico 1 (tinyint/enum numérico)
                if (array_key_exists('status', $row)) {
                    $row['status'] = 1;
                }
                DB::table('payments')->insert($row);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Intenta obtener el tipo real usando INFORMATION_SCHEMA primero y
     * luego SHOW COLUMNS como respaldo. Devuelve, por ejemplo:
     * - "enum('paid','pending')" / "tinyint(1)" / "varchar(10)" / "char(2)"
     */
    private function columnType(string $table, string $column): ?string
    {
        try {
            $db = DB::getDatabaseName();
            $row = DB::selectOne(
                "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?",
                [$db, $table, $column]
            );
            if ($row && isset($row->COLUMN_TYPE)) {
                return strtolower($row->COLUMN_TYPE);
            }
        } catch (\Throwable $e) {
            // continúa al fallback
        }

        try {
            $result = DB::select('SHOW COLUMNS FROM `'.$table.'` LIKE ?', [$column]);
            if (!empty($result) && isset($result[0]->Type)) {
                return strtolower($result[0]->Type);
            }
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * Para orders.status: valor acorde al tipo.
     */
    private function coerceOrderStatusValue(?string $colType, bool $paid)
    {
        if (!$colType) return $paid ? 'paid' : 'pending';
        $t = strtolower($colType);

        if (str_contains($t, 'int') || str_contains($t, 'bit') || str_contains($t, 'bool')) {
            return $paid ? 1 : 0;
        }

        if (preg_match("/^(enum)\\((.*)\\)$/", $t, $m)) {
            $raw = $m[2];
            $options = str_getcsv($raw, ',', "'");
            $optionsLower = array_map('strtolower', $options);

            $prefPaid   = ['paid','completed','shipped','success','ok','yes','1'];
            $prefUnpaid = ['pending','failed','cancelled','no','0'];

            $pref = $paid ? $prefPaid : $prefUnpaid;
            foreach ($pref as $p) {
                $idx = array_search($p, $optionsLower, true);
                if ($idx !== false) return $options[$idx];
            }
            return $options[0] ?? ($paid ? 'paid' : 'pending');
        }

        return $paid ? 'paid' : 'pending';
    }

    /**
     * Para payments.status (valor inicial).
     */
    private function coerceStatusValue(?string $colType)
    {
        if (!$colType) return 'paid';
        $t = strtolower($colType);

        // Numéricos -> 1
        if (str_contains($t, 'int') || str_contains($t, 'bit') || str_contains($t, 'bool')) {
            return 1;
        }

        // ENUM/SET
        if (preg_match("/^(enum|set)\\((.*)\\)$/", $t, $m)) {
            $raw = $m[2];
            $options = str_getcsv($raw, ',', "'");
            $optionsLower = array_map('strtolower', $options);

            $preferred = ['approved','paid','success','completed','ok','yes','1'];
            foreach ($preferred as $p) {
                $idx = array_search($p, $optionsLower, true);
                if ($idx !== false) return $options[$idx];
            }
            return $options[0] ?? 'paid';
        }

        // VARCHAR/CHAR(n)
        if (preg_match('/(var)?char\\((\\d+)\\)/', $t, $m)) {
            $maxLen = (int)$m[2];
            $candidates = ['paid','ok','1','yes','done','success','approved'];
            foreach ($candidates as $c) {
                if (strlen($c) <= $maxLen) return $c;
            }
            return substr('paid', 0, max(1, $maxLen));
        }

        // TEXT/otros
        return 'paid';
    }

    /**
     * Sanea contra el tipo: ENUM/SET (asegurar miembro), VARCHAR/CHAR(n) (recortar),
     * INT/BOOL (castear). Si no hay tipo, elige 'paid' o 1.
     */
    private function sanitizeColumnValue(string $table, string $column, $value, ?string $colType = null)
    {
        $t = $colType ?? $this->columnType($table, $column);
        if (!$t) {
            return is_numeric($value) ? (int)$value : 'paid';
        }
        $t = strtolower($t);

        // Numéricos
        if (str_contains($t, 'int') || str_contains($t, 'bit') || str_contains($t, 'bool')) {
            return is_numeric($value) ? (int)$value : 1;
        }

        // ENUM/SET
        if (preg_match("/^(enum|set)\\((.*)\\)$/", $t, $m)) {
            $raw = $m[2];
            $options = str_getcsv($raw, ',', "'");
            $optionsLower = array_map('strtolower', $options);
            $valLower = strtolower((string)$value);

            $idx = array_search($valLower, $optionsLower, true);
            if ($idx !== false) return $options[$idx];

            if ($valLower === '1' || $valLower === '0') {
                $map = ['1','yes','ok','paid','success','completed'];
                foreach ($map as $cand) {
                    $j = array_search($cand, $optionsLower, true);
                    if ($j !== false) return $options[$j];
                }
            }
            return $options[0] ?? 'paid';
        }

        // VARCHAR/CHAR(n)
        if (preg_match('/(var)?char\\((\\d+)\\)/', $t, $m)) {
            $maxLen = (int)$m[2];
            $str = (string)$value;
            if (strlen($str) > $maxLen) {
                return substr($str, 0, $maxLen);
            }
            return $str;
        }

        // TEXT/otros
        return (string)$value;
    }
}
