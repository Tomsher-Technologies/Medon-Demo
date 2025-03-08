<?php $__env->startSection('content'); ?>
    <?php
        $used = [];
    ?>

    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="h6">Abandoned Cart</h1>
                </div>
                <div class="card-body">
                    <table class="table table-bordered aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $carts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!in_array($cart->user_id, $used) && !in_array($cart->temp_user_id, $used)): ?>
                                    <?php
                                        $id = $cart->user_id ?? $cart->temp_user_id;
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                            if(!empty($cart->user->name)){
                                            echo $cart->user->name;
                                            }else{
                                            echo "GUEST";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo e($cart->created_at->format('d-m-Y h:i:s A')); ?></td>
                                        <td>
                                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                                href="<?php echo e(route('abandoned-cart.view', $cart->id)); ?>"
                                                title="View">
                                                <i class="las la-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                        $used[] = $id;
                                    ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="aiz-pagination mt-4">
                        <?php echo e($carts->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/reports/abandoned_cart.blade.php ENDPATH**/ ?>