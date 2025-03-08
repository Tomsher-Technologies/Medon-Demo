<?php $__env->startSection('content'); ?>
    <?php if(env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null): ?>
        <div class="">
            <div class="alert alert-danger d-flex align-items-center">
                Please Configure SMTP Setting to work all email sending functionality,
                <a class="alert-link ml-2" href="<?php echo e(route('smtp_settings.index')); ?>">Configure Now</a>
            </div>
        </div>
    <?php endif; ?>
    <?php if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions))): ?>
        <div class="row gutters-10">
            <div class="col-lg-12 text-right">
                <a href="<?php echo e(route('cache.clear', ['type' => 'counts'])); ?>"
                    class="btn btn-sm btn-soft-secondary btn-circle mr-2 mb-2">
                    <i class="la la-refresh fs-24"></i>
                </a>
            </div>



            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">

                <div class="card custom-card ">
                    <div class="card-body bg-1">
                        <div class="row">
                            <div
                                class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="">
                                    <img width="50" src="<?php echo e(static_asset('assets/img/team.png')); ?>">
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2 fs-15 count-t">Total Customer</div>
                                <div class="text-muted mb-1 fs-12 count-n "> <span
                                        class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                        <?php echo e($counts['totalUsersCount']); ?>

                                    </span> </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>




            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">

                <div class="card custom-card ">
                    <div class="card-body bg-2">
                        <div class="row">
                            <div
                                class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="">
                                    <img width="50" src="<?php echo e(static_asset('assets/img/Products.png')); ?>">
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2 fs-15 count-t">Total Products</div>
                                <div class="text-muted mb-1 fs-12 count-n"> <span
                                        class="text-dark fw-semibold fs-35 lh-1 vertical-bottom ">
                                        <?php echo e($counts['totalProductsCount']); ?>

                                    </span> </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>



            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">

                <div class="card custom-card ">
                    <div class="card-body bg-3">
                        <div class="row">
                            <div
                                class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="">
                                    <img width="50" src="<?php echo e(static_asset('assets/img/application.png')); ?>">
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2 fs-15 count-t">Total Product category</div>
                                <div class="text-muted mb-1 count-n"> <span
                                        class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                        <?php echo e($counts['categoryCount']); ?>

                                    </span> </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>



            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">

                <div class="card custom-card ">
                    <div class="card-body bg-4">
                        <div class="row">
                            <div
                                class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="">
                                    <img width="50" src="<?php echo e(static_asset('assets/img/badge.png')); ?>">
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2 fs-15 count-t">Total Product brand</div>
                                <div class="text-muted mb-1 count-n"> <span
                                        class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                        <?php echo e($counts['brandCount']); ?>

                                    </span> </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>



            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">

                <div class="card custom-card ">
                    <div class="card-body bg-5">
                        <div class="row">
                            <div
                                class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="">
                                    <img width="50" src="<?php echo e(static_asset('assets/img/sale (2).png')); ?>">
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2 fs-15 count-t">Total Sales Amount </div>
                                <div class="text-muted mb-1 count-n"> <span
                                        class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                        <?php echo e($counts['salesAmount']); ?>

                                    </span> </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>




            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">

                <div class="card custom-card ">
                    <div class="card-body bg-6">
                        <div class="row">
                            <div
                                class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="">
                                    <img width="50" src="<?php echo e(static_asset('assets/img/box (3).png')); ?>">
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2 fs-15 count-t">Total Orders </div>
                                <div class="text-muted mb-1 count-n"> <span
                                        class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                        <?php echo e($counts['orderCount']); ?>

                                    </span> </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>




            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">

                <div class="card custom-card ">
                    <div class="card-body bg-7">
                        <div class="row">
                            <div
                                class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="">
                                    <img width="50" src="<?php echo e(static_asset('assets/img/shopping-bag (5).png')); ?>">
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2 fs-15 count-t">Total Completed Orders </div>
                                <div class="text-muted mb-1 count-n"> <span
                                        class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                        <?php echo e($counts['orderCompletedCount']); ?>

                                    </span> </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>





            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">

                <div class="card custom-card ">
                    <div class="card-body bg-8">
                        <div class="row">
                            <div
                                class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="">
                                    <img width="50" src="<?php echo e(static_asset('assets/img/sign.png')); ?>">
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2 fs-15 count-t">Total Products Sold </div>
                                <div class="text-muted mb-1 count-n"> <span
                                        class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                        <?php echo e($counts['productsSold']); ?>

                                    </span> </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>










            <!--<div class="col-lg-12">-->
            <!--    <div class="row gutters-10">-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Customer-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3">-->
            <!--                        <?php echo e($counts['totalUsersCount']); ?>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class="d-block">Total</span>-->
            <!--                        Products-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3"><?php echo e($counts['totalProductsCount']); ?></div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Product category-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3"><?php echo e($counts['categoryCount']); ?></div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Product brand-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3"><?php echo e($counts['brandCount']); ?></div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->


            <!--<div class="col-lg-12">-->
            <!--    <div class="row gutters-10">-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Sales Amount-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3">-->
            <!--                        <?php echo e($counts['salesAmount']); ?>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class="d-block">Total</span>-->
            <!--                        Orders-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3"><?php echo e($counts['orderCount']); ?></div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Completed Orders-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3"><?php echo e($counts['orderCompletedCount']); ?></div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Products Sold-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3"><?php echo e($counts['productsSold']); ?></div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
    <?php endif; ?>


    <?php if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions))): ?>
        <div class="row gutters-10">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">Orders This Month</h6>
                        <a href="<?php echo e(route('cache.clear', ['type' => 'orderMonthGraph'])); ?>"
                            class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                            <i class="la la-refresh fs-24"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <canvas id="graph-1" class="w-100" height="400"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">Orders Past 12 Months</h6>
                        <a href="<?php echo e(route('cache.clear', ['type' => 'orderYearGraph'])); ?>"
                            class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                            <i class="la la-refresh fs-24"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <canvas id="graph-2" class="w-100" height="400"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">Total Sales This Month</h6>
                        <a href="<?php echo e(route('cache.clear', ['type' => 'salesYearGraph'])); ?>"
                            class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                            <i class="la la-refresh fs-24"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <canvas id="graph-3" class="w-100" height="400"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">Total Sales 12 Months</h6>
                        <a href="<?php echo e(route('cache.clear', ['type' => 'salesYearGraph'])); ?>"
                            class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                            <i class="la la-refresh fs-24"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <canvas id="graph-4" class="w-100" height="400"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h6 class="mb-0">Latest User Searches</h6>
                </div>
    
                <a href="<?php echo e(route('cache.clear', ['type' => 'searches'])); ?>"
                    class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                    <i class="la la-refresh fs-24"></i>
                </a>
    
                <a href="<?php echo e(route('user_search_report.index')); ?>" class="btn btn-primary">View All</a>
            </div>
            <div class="card-body">
                <table aria-describedby="" class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Search Key</th>
                            <th>User</th>
                            <th>IP Address</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $searches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $searche): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($searche->query); ?></td>
                                <td>
                                    <?php if($searche->user_id): ?>
                                        <a href="<?php echo e(route('user_search_report.index', ['user_id' => $searche->user_id])); ?>">
                                            <?php echo e($searche->user->name); ?>

                                        </a>
                                    <?php else: ?>
                                        GUEST
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($searche->ip_address); ?></td>
                                <td><?php echo e($searche->created_at->format('d-m-Y h:i:s A')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    
        <div class="card" id="topSellingProducts">
            <div class="card-header">
                <h6 class="mb-0">Top Selling Products</h6>
    
                <a href="<?php echo e(route('cache.clear', ['type' => 'topProducts'])); ?>"
                    class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                    <i class="la la-refresh fs-24"></i>
                </a>
            </div>
            <div class="card-body">
                <form class="row"  action="<?php echo e(route('admin.dashboard')); ?>" method="GET">
                    <div class="col-sm-6 col-md-4">
                        <label class="col-form-label"><?php echo e(translate('Sort by Category')); ?> :</label>
                        <select id="demo-ease" class="from-control aiz-selectpicker" data-width="100%" data-live-search="tru" name="category_id" data-selected=<?php echo e($category_id); ?>>
                            <?php $__currentLoopData = getAllCategories()->where('parent_id', 0); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>" <?php if( $category_id == $item->id): ?>  <?php echo e('selected'); ?> <?php endif; ?> )><?php echo e($item->name); ?></option>
                                <?php if($item->child): ?>
                                    <?php $__currentLoopData = $item->child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo $__env->make('backend.product.categories.menu_child_category', [
                                            'category' => $cat,
                                            'old_data' => $category_id,
                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="col-sm-6 col-md-4">
                        <label class="col-form-label"></label>
                        <button class="btn btn-warning mt-4" type="submit">Filter</button>
                        <a class="btn btn-info mt-4" href="<?php echo e(route('admin.dashboard')); ?>" >Reset</a>
                        <a href="<?php echo e(route('export.top_selling_report')); ?>"  class="btn btn-danger mt-4" style="border-radius: 30px;">Excel Export</a>
                    </div>
                </form>

                <?php if(!empty($topProducts[0])): ?>
                    <div class="aiz-carousel gutters-10 half-outside-arrow mt-4" data-items="6" data-xl-items="5" data-lg-items="4"
                    data-md-items="3" data-sm-items="2" data-arrows='true'>
                    <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="carousel-box">
                            <div
                                class="aiz-card-box border border-light rounded shadow-sm hov-shadow-md mb-2 has-transition bg-white">
                                <div class="position-relative">
                                    <img class="img-fit lazyload mx-auto h-210px"
                                        src="<?php echo e(get_product_image($product->thumbnail_img, '300')); ?>"
                                        data-src="<?php echo e(get_product_image($product->thumbnail_img, '300')); ?>" alt="<?php echo e($product->name); ?>"
                                        onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                                </div>
                                <div class="p-md-3 p-2 text-left">
                                    <div class="fs-15">
                                        <?php if(home_base_price($product) != home_discounted_base_price($product)): ?>
                                            <del class="fw-600 opacity-50 mr-1"><?php echo e(home_base_price($product)); ?></del>
                                        <?php endif; ?>
                                        <span class="fw-700 text-primary"><?php echo e(home_discounted_base_price($product)); ?></span>
                                    </div>
                                    <h3 class="fw-600 fs-14 text-truncate-2 lh-1-4 mb-0">
                                        <a href="javascript:void(0)" class="d-block text-reset"><?php echo e($product->name); ?></a>
                                    </h3>
                                    <div class="fs-13">
                                        Total sales: <?php echo e($product->order_details_sum_quantity); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="col-sm-12 text-center mt-5">
                        <h5>No Products Found!</h5>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    <?php endif; ?>

    <?php if(Auth::user()->user_type == 'staff' && Auth::user()->shop_id != null): ?>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="row">

                    <div class="col-lg-3 col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="<?php echo e(asset('admin_assets/assets/svg/icons/shopping-bag-icon2.svg')); ?>"
                                            alt="Credit Card" class="rounded">
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="cardOpt6">
                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>

                                        </div>
                                    </div>
                                </div>
                                <span class="d-block">Total Orders</span>
                                <h4 class="card-title mb-1"><?php echo e($counts['shopOrderCount']); ?></h4>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="<?php echo e(asset('admin_assets/assets/svg/icons/shopping-bag-icon4.svg')); ?>"
                                            alt="Credit Card" class="rounded">
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="cardOpt6">
                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>

                                        </div>
                                    </div>
                                </div>
                                <span class="d-block">Today Orders</span>
                                <h4 class="card-title mb-1"><?php echo e($counts['shopTodayOrderCount']); ?></h4>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="<?php echo e(asset('admin_assets/assets/svg/icons/shopping-bag-icon.svg')); ?>"
                                            alt="Credit Card" class="rounded">
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="cardOpt6">
                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>

                                        </div>
                                    </div>
                                </div>
                                <span class="d-block">Pending Orders</span>
                                <h4 class="card-title mb-1"><?php echo e($counts['shopPendingCount']); ?></h4>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="<?php echo e(asset('admin_assets/assets/svg/icons/shopping-bag-icon3.svg')); ?>"
                                            alt="Credit Card" class="rounded">
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="cardOpt6">
                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>

                                        </div>
                                    </div>
                                </div>
                                <span class="d-block">Completed Orders</span>
                                <h4 class="card-title mb-1"><?php echo e($counts['shopCompletedCount']); ?></h4>
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Total Income -->
            <div class="col-md-12 col-lg-12 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-9">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Total Orders<small class="card-subtitle">Yearly report overview</small></h5><br>
                                <input type="text" class="form-control col-3" placeholder="Select Year" name="yearFilter" id="yearFilter" value="<?php echo e($year); ?>" readonly>
                            </div>
                            <div class="card-body">
                                <canvas id="totalOrdersChart" class="w-100" height="310"></canvas>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">Order Report</h5>
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="report-list">
                                    <div class="report-list-item rounded-2 mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="report-list-icon shadow-sm me-2">
                                                <img src="<?php echo e(asset('admin_assets/assets/svg/icons/shopping-bag-icon2.svg')); ?>"
                                                    width="22" height="22" alt="Paypal">
                                            </div>
                                            <div class="d-flex justify-content-between align-items-end w-100 flex-wrap gap-2">
                                                <div class="d-flex flex-column">
                                                    <span>This Week</span>
                                                    <h5 class="mb-0"><?php echo e($counts['shopWeekOrderCount']); ?></h5>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="report-list-item rounded-2 mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="report-list-icon shadow-sm me-2">
                                                <img src="<?php echo e(asset('admin_assets/assets/svg/icons/shopping-bag-icon2.svg')); ?>"
                                                    width="22" height="22" alt="Shopping Bag">
                                            </div>
                                            <div class="d-flex justify-content-between align-items-end w-100 flex-wrap gap-2">
                                                <div class="d-flex flex-column">
                                                    <span>This Month</span>
                                                    <h5 class="mb-0"><?php echo e($counts['shopMonthOrderCount']); ?></h5>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="report-list-item rounded-2 mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="report-list-icon shadow-sm me-2">
                                                <img src="<?php echo e(asset('admin_assets/assets/svg/icons/shopping-bag-icon2.svg')); ?>"
                                                    width="22" height="22" alt="Wallet">
                                            </div>
                                            <div class="d-flex justify-content-between align-items-end w-100 flex-wrap gap-2">
                                                <div class="d-flex flex-column">
                                                    <span>This Year</span>
                                                    <h5 class="mb-0"><?php echo e($counts['shopYearOrderCount']); ?></h5>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="report-list-item rounded-2 mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="report-list-icon shadow-sm me-2">
                                                <img src="<?php echo e(asset('admin_assets/assets/svg/icons/shopping-bag-icon2.svg')); ?>"
                                                    width="22" height="22" alt="Wallet">
                                            </div>
                                            <div class="d-flex justify-content-between align-items-end w-100 flex-wrap gap-2">
                                                <div class="d-flex flex-column">
                                                    <span>Last Year</span>
                                                    <h5 class="mb-0"><?php echo e($counts['shopLYearOrderCount']); ?></h5>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Income -->
            </div>
        </div>
    <?php endif; ?>


    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('styles'); ?>
<style>
    .card-body-after {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: -1px;
        display: block;
        width: 0;
        border-left: 1px solid #d9dee3;
    }
    .card-header, .card-footer {
        border-color: #d9dee3;
    }
    .avatar {
        position: relative;
        width: 2.375rem;
        height: 2.375rem;
        cursor: pointer;
    }
    .flex-shrink-0 {
        flex-shrink: 0 !important;
    }

    .card-subtitle {
        margin-top: calc(-0.5*var(--bs-card-title-spacer-y));
        margin-bottom: 0;
        color: grey;
        font-size: 12px;
        margin-left: 10px;
    }

    .report-list .report-list-item {
        background-color: #f5f5f9;
    }
    .report-list .report-list-item {
        padding: 0.75rem;
    }
    .rounded-2 {
        border-radius: 0.375rem !important;
    }
        
    .report-list .report-list-icon {
        background-color: #fff;
        border-radius: 0.375rem;
    }
    html:not([dir=rtl]) .me-2 {
        margin-right: 0.5rem !important;
    }
    .report-list .report-list-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        min-width: 44px;
    }
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(161,172,184,.4) !important;
    }
    .gap-2 {
        gap: 0.5rem !important;
    }
</style>

<link rel="stylesheet" href="<?php echo e(static_asset('assets/css/bootstrap-datepicker.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<script src="<?php echo e(static_asset('assets/js/bootstrap-datepicker.js')); ?>"></script>

<script type="text/javascript">

    if (window.location.search.match(/(category_id)/)) {
        $('html, body').animate({
            scrollTop: $(document).height()
        }, 1000); // scrolls to the absolute bottom in 1 second
    }


    $("#yearFilter").datepicker({
        format: "yyyy",
        viewMode: "years", 
        minViewMode: "years"
    });
    $("#yearFilter").on('change',function(){
        window.location.href = "<?php echo e(route('admin.dashboard')); ?>?year="+$("#yearFilter").val();
    });

    AIZ.plugins.chart('#totalOrdersChart', {
        type: 'bar',
        data: {
            labels: <?php echo $shopOrderYearGraph['all']['months']; ?>,
            datasets: [{
                type: 'bar',
                label: 'No:of orders recived',
                data: <?php echo e($shopOrderYearGraph['all']['counts']); ?>,
                backgroundColor: [
                    <?php for($i = 0; $i < 12; $i++): ?>
                        'rgba(55, 125, 255, 0.4)',
                    <?php endfor; ?>
                ],
                borderColor: [
                    <?php for($i = 0; $i < 12; $i++): ?>
                        'rgba(55, 125, 255, 1)',
                    <?php endfor; ?>
                ],
                borderWidth: 1
            }, {
                type: 'bar',
                label: 'No:of orders completed',
                data: <?php echo e($shopOrderYearGraph['completed']['counts']); ?>,
                backgroundColor: [
                    <?php for($i = 0; $i < 12; $i++): ?>
                        'rgba(43, 255, 112, 0.4)',
                    <?php endfor; ?>
                ],
                borderColor: [
                    <?php for($i = 0; $i < 12; $i++): ?>
                        'rgba(43, 255, 112, 1)',
                    <?php endfor; ?>
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true,
                        precision: 0
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                }
            }
        }
    });




    AIZ.plugins.chart('#graph-1', {
        type: 'bar',
        data: {
            labels: [
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    '<?php echo e($day); ?>',
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            datasets: [{
                label: 'No:of orders recived this month',
                data: [
                    <?php echo e($orderMonthGraph['monthOrdersData']); ?>

                ],
                backgroundColor: [
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(55, 125, 255, 0.4)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderColor: [
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(55, 125, 255, 1)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderWidth: 1
            }, {
                label: 'No:of orders completed this month',
                data: [
                    <?php echo e($orderMonthGraph['monthOrdersCompletedData']); ?>

                ],
                backgroundColor: [
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(43, 255, 112, 0.4)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderColor: [
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(43, 255, 112, 1)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true,
                        precision: 0
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                }
            }
        }
    });


    AIZ.plugins.chart('#graph-2', {
        type: 'bar',
        data: {
            labels: <?php echo $orderYearGraph['all']['months']; ?>,
            datasets: [{
                type: 'bar',
                label: 'No:of orders recived',
                data: <?php echo e($orderYearGraph['all']['counts']); ?>,
                backgroundColor: [
                    <?php for($i = 0; $i < 12; $i++): ?>
                        'rgba(55, 125, 255, 0.4)',
                    <?php endfor; ?>
                ],
                borderColor: [
                    <?php for($i = 0; $i < 12; $i++): ?>
                        'rgba(55, 125, 255, 1)',
                    <?php endfor; ?>
                ],
                borderWidth: 1
            }, {
                type: 'bar',
                label: 'No:of orders completed',
                data: <?php echo e($orderYearGraph['completed']['counts']); ?>,
                backgroundColor: [
                    <?php for($i = 0; $i < 12; $i++): ?>
                        'rgba(43, 255, 112, 0.4)',
                    <?php endfor; ?>
                ],
                borderColor: [
                    <?php for($i = 0; $i < 12; $i++): ?>
                        'rgba(43, 255, 112, 1)',
                    <?php endfor; ?>
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true,
                        precision: 0
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                }
            }
        }
    });

    AIZ.plugins.chart('#graph-3', {
        type: 'bar',
        data: {
            labels: [
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    '<?php echo e($day); ?>',
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            datasets: [{
                label: 'Sales this month',
                data: [
                    <?php echo e($salesMonthGraph['monthSalesData']); ?>

                ],
                backgroundColor: [
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(55, 125, 255, 0.4)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderColor: [
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        'rgba(55, 125, 255, 1)',
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true,
                        precision: 0
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                }
            }
        }
    });

    AIZ.plugins.chart('#graph-4', {
        type: 'line',
        data: {
            labels: <?php echo $orderYearGraph['all']['months']; ?>,
            datasets: [{
                type: 'line',
                label: 'Total sales',
                data: <?php echo e($salesYearGraph['counts']); ?>,
                backgroundColor: [
                    <?php for($i = 0; $i < 12; $i++): ?>
                        'rgba(43, 255, 112, 0.4)',
                    <?php endfor; ?>
                ],
                borderColor: [
                    <?php for($i = 0; $i < 12; $i++): ?>
                        'rgba(43, 255, 112, 1)',
                    <?php endfor; ?>
                ],
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true,
                        precision: 0
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                }
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/dashboard.blade.php ENDPATH**/ ?>