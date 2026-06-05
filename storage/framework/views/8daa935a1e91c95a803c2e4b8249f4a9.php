<?php if($paginator->hasPages()): ?>
    <nav class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3" role="navigation" aria-label="Paginación">
        
        <div class="text-muted small">
            <?php
                $from = ($paginator->currentPage() - 1) * $paginator->perPage() + 1;
                $to   = min($paginator->currentPage() * $paginator->perPage(), $paginator->total());
            ?>
            Mostrando <strong><?php echo e($from); ?></strong> a <strong><?php echo e($to); ?></strong> de <strong><?php echo e($paginator->total()); ?></strong> resultados
        </div>

        
        <ul class="pagination mb-0">
            
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">Anterior</span>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">Anterior</a>
                </li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_string($element)): ?>
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
                <?php endif; ?>

                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                        <?php else: ?>
                            <li class="page-item"><a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">Siguiente</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">Siguiente</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/vendor/pagination/procafes.blade.php ENDPATH**/ ?>