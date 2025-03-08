<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3"><?php echo e(translate('All Shops')); ?></h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="<?php echo e(route('admin.shops.create')); ?>" class="btn btn-circle btn-info">
				<span><?php echo e(translate('Add New Shop')); ?></span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <form class="" id="sort_sellers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6"><?php echo e(translate('Shops')); ?></h5>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search"
                        name="search" <?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?>
                        placeholder="<?php echo e(translate('Type search word & Enter')); ?>">
                </div>
            </div>
        </div>
    </form>
    <div class="card-body">
        
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th >#</th>
                    <th><?php echo e(translate('Branch Name')); ?></th>
                    <th><?php echo e(translate('Address')); ?></th>
                    <th ><?php echo e(translate('Phone')); ?></th>
                    <th ><?php echo e(translate('Email')); ?></th>
                    <th ><?php echo e(translate('Working Hours')); ?></th>
                    <th ><?php echo e(translate('Status')); ?></th>
                    <th class="text-center" width="10%"><?php echo e(translate('Action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(($key+1) + ($shops->currentPage() - 1)*$shops->perPage()); ?></td>
                        <td>
                            <?php echo e($shop->name); ?>

                        </td>
                        <td>
                            <?php echo e($shop->address); ?>

                        </td>
                        <td><?php echo e($shop->phone); ?></td>
                        <td><?php echo e($shop->email); ?></td>
                        <td><?php echo e($shop->working_hours); ?></td>
                        <td>
                            <?php if($shop->status == 1): ?>
                                <span class="badge badge-soft-success" style="width:40px;">Active </span>
                            <?php else: ?>
                                <span class="badge badge-soft-danger w-40" style="width:50px;">Inactive </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo e(route('admin.shops.edit', $shop)); ?>"
                                class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                title="<?php echo e(translate('Edit')); ?>">
                                <i class="las la-edit"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="aiz-pagination">
            <?php echo e($shops->appends(request()->input())->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/shop/index.blade.php ENDPATH**/ ?>