<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="h6">Abandoned Cart Details</h1>

                    <a class="btn btn-primary" href="<?php echo e(Session::has('cart_last_url') ? Session::get('cart_last_url') : route('abandoned-cart.index')); ?>">
                        Go Back
                    </a>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered aiz-table invoice-summary">
                                <thead>
                                    <tr class="bg-trans-dark">
                                        <th data-breakpoints="lg" class="min-col">#</th>
                                        <th width="10%">Photo</th>
                                        <th class="text-uppercase">Description</th>
                                        <th data-breakpoints="lg" class="min-col text-center text-uppercase">
                                            Date added</th>
                                        <th data-breakpoints="lg" class="min-col text-center text-uppercase">
                                            Qty</th>
                                        <th data-breakpoints="lg" class="min-col text-center text-uppercase">
                                            Price</th>
                                        <th data-breakpoints="lg" class="min-col text-right text-uppercase">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $carts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td>
                                                
                                                    <img height="50"
                                                        src="<?php echo e(asset($cart->product->thumbnail_img)); ?>">
                                                    
                                            </td>
                                            <td>
                                                <strong>
                                                    
                                                    <?php echo e($cart->product->name); ?>

                                                </strong>
                                                <small><?php echo e($cart->variation); ?></small>
                                            </td>
                                            <td><?php echo e($cart->created_at->format('d-m-Y h:i:s A')); ?></td>
                                            <td class="text-center"><?php echo e($cart->quantity); ?></td>
                                            <td class="text-center">
                                                <?php echo e(single_price($cart->price)); ?></td>
                                            <td class="text-center"><?php echo e(single_price($cart->price * $cart->quantity)); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Grand Total</b></td>
                                        <td><b><?php echo e(single_price($total_price)); ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/reports/abandoned_cart_details.blade.php ENDPATH**/ ?>