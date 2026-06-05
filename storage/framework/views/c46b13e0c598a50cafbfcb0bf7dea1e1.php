<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo e($doc_type); ?> <?php echo e($series); ?>-<?php echo e($number); ?></title>
  <style>
    *{ font-family: DejaVu Sans, sans-serif; }
    body{ font-size: 12px; }
    .w-100{ width:100%; } .text-right{ text-align:right; } .text-center{ text-align:center; }
    .mb-1{ margin-bottom:6px; } .mb-2{ margin-bottom:12px; } .mb-3{ margin-bottom:18px; }
    .box{ border:1px solid #888; border-radius:6px; padding:10px; }
    table{ border-collapse: collapse; width:100%; }
    th, td{ border:1px solid #bbb; padding:6px; }
    th{ background:#f2f2f2; }
    .totals td{ border:none; }
  </style>
</head>
<body>

  <table class="w-100 mb-3" style="border:none">
    <tr>
      <td style="border:none">
        <h2 class="mb-1">PROCAFES</h2>
        <div>JR. CAFETALES 123 – PICHANAKI</div>
      </td>
      <td class="text-right" style="border:none">
        <div class="box">
          <div><strong><?php echo e($doc_type); ?></strong></div>
          <div><strong><?php echo e($series); ?> - <?php echo e($number); ?></strong></div>
          <div>Fecha: <?php echo e($issue_date); ?></div>
        </div>
      </td>
    </tr>
  </table>

  <div class="box mb-3">
    <strong>Cliente</strong><br>
    Documento: <?php echo e($customer['document'] ?: '—'); ?><br>
    Nombre / Razón social: <?php echo e($customer['name'] ?: '—'); ?><br>
    Dirección: <?php echo e($customer['address'] ?: '—'); ?>

  </div>

  <table class="mb-2">
    <thead>
      <tr>
        <th style="width:28px">#</th>
        <th>Descripción</th>
        <th style="width:70px" class="text-right">Cant.</th>
        <th style="width:90px" class="text-right">P. Unit.</th>
        <th style="width:90px" class="text-right">Op. Grav.</th>
        <th style="width:70px" class="text-right">IGV</th>
        <th style="width:90px" class="text-right">Total</th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td class="text-center"><?php echo e($it['n']); ?></td>
          <td><?php echo e($it['description']); ?></td>
          <td class="text-right"><?php echo e(number_format($it['qty'], 2)); ?></td>
          <td class="text-right"><?php echo e(number_format($it['unit_price'], 2)); ?></td>
          <td class="text-right"><?php echo e(number_format($it['line_opg'], 2)); ?></td>
          <td class="text-right"><?php echo e(number_format($it['line_igv'], 2)); ?></td>
          <td class="text-right"><?php echo e(number_format($it['line_total'], 2)); ?></td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>

  <table class="w-100 totals">
    <tr>
      <td class="text-right">Op. Gravadas:</td>
      <td style="width:120px" class="text-right"><?php echo e(number_format($totals['op_gravadas'], 2)); ?></td>
    </tr>
    <tr>
      <td class="text-right">IGV (18%):</td>
      <td class="text-right"><?php echo e(number_format($totals['igv'], 2)); ?></td>
    </tr>
    <tr>
      <td class="text-right"><strong>Total <?php echo e($totals['currency']); ?>:</strong></td>
      <td class="text-right"><strong><?php echo e(number_format($totals['total'], 2)); ?></strong></td>
    </tr>
  </table>

  <p class="mb-1">* Documento interno de prueba (sin validez tributaria).</p>
</body>
</html>
 <?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/admin/billing/pdf.blade.php ENDPATH**/ ?>