<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3"><?php echo e(translate('All Prescriptions')); ?></h1>
		</div>
		
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6"><?php echo e(translate('Prescriptions')); ?></h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo e(translate('Name')); ?></th>
                    <th><?php echo e(translate('Email')); ?></th>
                    <th><?php echo e(translate('Phone')); ?></th>
                    <th width="20%"><?php echo e(translate('Comment')); ?></th>
                    <th class="text-center" width="10%"><?php echo e(translate('Emirated ID Front')); ?></th>
                    <th class="text-center" width="10%"><?php echo e(translate('Emirated ID Back')); ?></th>
                    <th class="text-center" width="10%"><?php echo e(translate('Prescription')); ?></th>
                    <th class="text-center" ><?php echo e(translate('Date')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $prescription; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(($key+1) + ($prescription->currentPage() - 1)*$prescription->perPage()); ?></td>
                        <td><?php echo e($pre->user->name ??  $pre->name); ?> <?php echo ($pre->user) ? '<span class="badge badge-success" style="width:35px;">User</span>' : '<span class="badge badge-danger" style="width:40px;">Guest</span>'; ?></td>
                        <td><?php echo e($pre->user->email ??  $pre->email); ?></td>
                        <td><?php echo e($pre->user->phone ??  $pre->phone); ?></td>
                        <td><?php echo e($pre->comment); ?></td>
                        <td class="text-center">
                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="<?php echo e(asset($pre->user->eid_image_front ??  $pre->emirates_id_front)); ?>" target="_blank">
                                <i class="las la-file"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(asset($pre->user->eid_image_back ??  $pre->emirates_id_back)); ?>" target="_blank">
                                <i class="las la-file"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="<?php echo e(asset($pre->prescription)); ?>" target="_blank">
                                <i class="las la-file"></i>
                            </a>
                        </td>
                        <td><?php echo e(date('d-m-Y H:i a', strtotime($pre->created_at))); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="aiz-pagination">
            <?php echo e($prescription->appends(request()->input())->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/website_settings/prescriptions.blade.php ENDPATH**/ ?>