<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Staff Information')); ?></h5>
            </div>

            <form action="<?php echo e(route('staffs.update', $staff->id)); ?>" method="POST">
                <input name="_method" type="hidden" value="PATCH">
            	<?php echo csrf_field(); ?>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name"><?php echo e(translate('Name')); ?></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="<?php echo e(translate('Name')); ?>" id="name" name="name" value="<?php echo e(old('name',$staff->user->name)); ?>" class="form-control" >
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email"><?php echo e(translate('Email')); ?></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="<?php echo e(translate('Email')); ?>" id="email" name="email" value="<?php echo e(old('email',$staff->user->email)); ?>" class="form-control" >
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="phone"><?php echo e(translate('Phone')); ?></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="971" id="phone" name="phone" value="<?php echo e(old('phone',$staff->user->phone)); ?>" class="form-control" >
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="password"><?php echo e(translate('Password')); ?></label>
                        <div class="col-sm-9">
                            <input type="password" autocomplete="new-password" placeholder="<?php echo e(translate('Password')); ?>" id="password" name="password" class="form-control">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="shop_id"><?php echo e(translate('Shop')); ?></label>
                        <div class="col-sm-9">
                            <select id="shop_id" name="shop_id" class="form-control aiz-selectpicker" data-live-search="true" data-max-options="10" >
                                <?php
                                    $shops = getActiveShops();
                                ?>
                                <option value="">Select Shop</option>
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($shop->id == old('shop_id',$staff->user->shop_id)): ?> <?php echo e('selected'); ?> <?php endif; ?> value="<?php echo e($shop->id); ?>"><?php echo e($shop->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name"><?php echo e(translate('Role')); ?></label>
                        <div class="col-sm-9">
                            <select name="role_id" required class="form-control aiz-selectpicker">
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>" <?php if(old('role_id',$staff->role_id) == $role->id) echo "selected"; ?> ><?php echo e($role->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label"><?php echo e(translate('Active Status')); ?></label>
                        <div class="col-md-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="status" value="1" <?php if($staff->user->banned == 0): ?> checked <?php endif; ?>>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary"><?php echo e(translate('Save')); ?></button>
                        <a href="<?php echo e(route('staffs.index')); ?>"  class="btn btn-sm btn-warning"><?php echo e(translate('Cancel')); ?></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script>
    const phoneInput = document.getElementById('phone');

    phoneInput.addEventListener('focus', function() {
        if (!phoneInput.value) {
            phoneInput.value = '971';
        }
    });

    phoneInput.addEventListener('blur', function() {
        if (phoneInput.value === '971') {
            phoneInput.value = '';  // Clear if only the code is left
        }
    });
</script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/staff/staffs/edit.blade.php ENDPATH**/ ?>