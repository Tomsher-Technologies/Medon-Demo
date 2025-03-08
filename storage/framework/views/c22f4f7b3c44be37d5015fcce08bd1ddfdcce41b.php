<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3"><?php echo e(translate('Product wise stock report')); ?></h1>
	</div>
</div>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <!--card body-->
            <div class="card-body">
                <form action="<?php echo e(route('stock_report.index')); ?>" method="GET">
                    <div class="form-group row">
                        
                        <div class="col-sm-6 col-md-4">
                            <label class="col-form-label"><?php echo e(translate('Sort by Category')); ?> :</label>
                            <select id="demo-ease" class="from-control aiz-selectpicker" data-width="100%" data-live-search="tru" name="category_id" data-selected=<?php echo e($sort_by); ?>>
                                <?php $__currentLoopData = getAllCategories()->where('parent_id', 0); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>" <?php if( $sort_by == $item->id): ?>  <?php echo e('selected'); ?> <?php endif; ?> )><?php echo e($item->name); ?></option>
                                    <?php if($item->child): ?>
                                        <?php $__currentLoopData = $item->child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo $__env->make('backend.product.categories.menu_child_category', [
                                                'category' => $cat,
                                                'old_data' => $sort_by,
                                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="col-form-label">Search Product Name/SKU :</label>
                            <input type="text" class="form-control form-control-sm" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="Type & Enter">
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="col-form-label"></label>
                            <button class="btn btn-warning mt-4" type="submit">Filter</button>
                            <a class="btn btn-info mt-4" href="<?php echo e(route('stock_report.index')); ?>" >Reset</a>
                            <a href="<?php echo e(route('export.stock_report')); ?>"  class="btn btn-danger mt-4" style="border-radius: 30px;">Excel Export</a>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Sl No.</th>
                            <th>Product SKU</th>
                            <th>Product Name</th>
                            <th class="text-center">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($key + 1 + ($products->currentPage() - 1) * $products->perPage()); ?></td>
                                <td> <?php echo e($product->sku); ?> </td>
                                <td><?php echo e($product->name); ?></td>
                                <td class="text-center"><?php echo e($product->stocks[0]->qty ?? 0); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    <?php echo e($products->appends(request()->input())->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/reports/stock_report.blade.php ENDPATH**/ ?>