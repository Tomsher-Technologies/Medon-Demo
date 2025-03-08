<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
        <h1 class="h3"><?php echo e(translate('All Notifications')); ?></h1>
    </div>
</div>


<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <form class="" id="sort_customers" action="" method="GET">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-0 h6"><?php echo e(translate('Notifications')); ?></h5>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="list-group-item d-flex justify-content-between align-items- py-3">
                                <div class="media text-inherit">
                                    <div class="media-body">
                                        <p class="mb-1 text-truncate-2">
                                            <?php echo e($notification->data['message'] ?? ''); ?>

                                        </p>
                                        <small class="text-muted">
                                            <?php echo e(date("F j Y, g:i a", strtotime($notification->created_at))); ?>

                                        </small>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="list-group-item">
                                <div class="py-4 text-center fs-16">No notification found</div>
                            </li>
                        <?php endif; ?>
                    </ul>
                    
                    <?php echo e($notifications->links()); ?>

                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/notification/index.blade.php ENDPATH**/ ?>