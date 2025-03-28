<?php $__env->startSection('content'); ?>
    <div class="col-lg-7 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Role Information')); ?></h5>
            </div>
            <form action="<?php echo e(route('roles.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label" for="name"><?php echo e(translate('Name')); ?></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="<?php echo e(translate('Name')); ?>" id="name" name="name"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="card-header">
                        <h5 class="mb-0 h6">Permissions</h5>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label class="col-md-2 col-from-label"></label>
                        <div class="col-md-8">

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products View</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="2">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products Create</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="25">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products Edit</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="26">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products Delete</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="27">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products Import</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="30">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products Export</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="31">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">All Orders</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="3">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Order Return Requests</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="4">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Order Cancel Requests</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="5">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row d-none">
                                <div class="col-md-10">
                                    <label class="col-from-label">Product Enquiry</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="28">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Contact Enquiries</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="29">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Customers</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="8">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Reports</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="10">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Marketing</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="11">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Website Setup</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="13">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Setup & Configurations</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="14">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Staffs</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="20">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Uploaded Files</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="22">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Prescriptions</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="23">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Manage Shops</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="21">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Manage Delivery Boys</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="34">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">App Setup</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="32">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Manage Push Notifications</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="33">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary"><?php echo e(translate('Save')); ?></button>
                    </div>
                </div>
                </from>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/staff/staff_roles/create.blade.php ENDPATH**/ ?>