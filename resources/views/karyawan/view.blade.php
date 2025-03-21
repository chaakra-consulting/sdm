@extends('layouts.main')

@section('content')
<!-- Start::app-content -->

    <div class="container-fluid">
        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="ps-0">
                            <div class="main-profile-overview">
                                <span class="avatar avatar-xxl avatar-rounded main-img-user profile-user user-profile">
                                    <img src="{{ asset('Tema/dist/assets/images/faces/6.jpg') }}" alt=""
                                        class="profile-img">
                                    <a href="javascript:void(0);"
                                        class="badge rounded-pill bg-primary avatar-badge profile-edit">
                                        <input type="file" name="photo"
                                            class="position-absolute profile-change w-100 h-100 op-0" id="">
                                        <i class="fe fe-camera"></i>
                                    </a>
                                </span>
                                <div class="d-flex justify-content-between mb-4">
                                    <div>
                                        <h5 class="main-profile-name">Petey Cruiser</h5>
                                        <p class="main-profile-name-text text-muted">Web Designer</p>
                                    </div>
                                </div>
                                <h6 class="fs-14">Bio</h6>
                                <div class="main-profile-bio">
                                    pleasure rationally encounter but because pursue consequences that are
                                    extremely painful.occur in which toil and pain can procure him some great
                                    pleasure.. <a href="javascript:void(0);">More</a>
                                </div><!-- main-profile-bio -->
                                <div class="row">
                                    <div class="col-md-4 col mb20">
                                        <h5 class="fs-17">947</h5>
                                        <h6 class="text-small text-muted fs-14 mb-0">Followers</h6>
                                    </div>
                                    <div class="col-md-4 col mb20">
                                        <h5 class="fs-17">583</h5>
                                        <h6 class="text-small text-muted fs-14 mb-0">Tweets</h6>
                                    </div>
                                    <div class="col-md-4 col mb20">
                                        <h5 class="fs-17">48</h5>
                                        <h6 class="text-small text-muted fs-14 mb-0">Posts</h6>
                                    </div>
                                </div>
                                <hr class="border-0">
                                <label class="main-content-label fs-13 mb-4">Social</label>
                                <div class="main-profile-social-list">
                                    <div class="media">
                                        <div class="media-icon bg-primary-transparent text-primary">
                                            <i class="icon ion-logo-github"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>Github</span> <a href="javascript:void(0);"
                                                class="text-primary">github.com/spruko</a>
                                        </div>
                                    </div>
                                    <div class="media">
                                        <div class="media-icon bg-success-transparent text-success">
                                            <i class="ri-twitter-x-fill"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>Twitter</span> <a href="javascript:void(0);"
                                                class="text-primary">twitter.com/spruko.me</a>
                                        </div>
                                    </div>
                                    <div class="media">
                                        <div class="media-icon bg-info-transparent text-info">
                                            <i class="icon ion-logo-linkedin"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>Linkedin</span> <a href="javascript:void(0);"
                                                class="text-primary">linkedin.com/in/spruko</a>
                                        </div>
                                    </div>
                                    <div class="media">
                                        <div class="media-icon bg-danger-transparent text-danger">
                                            <i class="icon ion-md-link"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>My Portfolio</span> <a href="javascript:void(0);"
                                                class="text-primary">spruko.com/</a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-0">
                                <h6 class="fs-14">Skills</h6>
                                <div class="skill-bar mb-4 clearfix mt-3">
                                    <span>HTML5 / CSS3</span>
                                    <div class="progress progress-sm mt-2">
                                        <div class="progress-bar bg-primary-gradient" role="progressbar"
                                            aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%">
                                        </div>
                                    </div>
                                </div>
                                <!--skill bar-->
                                <div class="skill-bar mb-4 clearfix">
                                    <span>Javascript</span>
                                    <div class="progress progress-sm mt-2">
                                        <div class="progress-bar bg-danger-gradient" role="progressbar"
                                            aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 89%">
                                        </div>
                                    </div>
                                </div>
                                <!--skill bar-->
                                <div class="skill-bar mb-4 clearfix">
                                    <span>Bootstrap</span>
                                    <div class="progress progress-sm mt-2">
                                        <div class="progress-bar bg-success-gradient" role="progressbar"
                                            aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                        </div>
                                    </div>
                                </div>
                                <!--skill bar-->
                                <div class="skill-bar clearfix">
                                    <span>Coffee</span>
                                    <div class="progress progress-sm mt-2">
                                        <div class="progress-bar bg-info-gradient" role="progressbar" aria-valuenow="85"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 95%"></div>
                                    </div>
                                </div>
                                <!--skill bar-->
                            </div><!-- main-profile-overview -->
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="main-content-label tx-13 mg-b-25">
                            contact
                        </div>
                        <div class="main-profile-contact-list">
                            <div class="media">
                                <div class="media-icon bg-primary-transparent text-primary">
                                    <i class="icon ion-md-phone-portrait"></i>
                                </div>
                                <div class="media-body">
                                    <span>Mobile</span>
                                    <div>
                                        +245 354 654
                                    </div>
                                </div>
                            </div>
                            <div class="media">
                                <div class="media-icon bg-success-transparent text-success">
                                    <i class="icon ion-logo-slack"></i>
                                </div>
                                <div class="media-body">
                                    <span>Slack</span>
                                    <div>
                                        @spruko.w
                                    </div>
                                </div>
                            </div>
                            <div class="media">
                                <div class="media-icon bg-info-transparent text-info">
                                    <i class="icon ion-md-locate"></i>
                                </div>
                                <div class="media-body">
                                    <span>Current Address</span>
                                    <div>
                                        San Francisco, CA
                                    </div>
                                </div>
                            </div>
                        </div><!-- main-profile-contact-list -->
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4 main-content-label">Personal Information</div>
                        <form class="form-horizontal">
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Language</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control" data-trigger name="choices-single-default"
                                            id="choices-single-default1">
                                            <option>Us English</option>
                                            <option>Arabic</option>
                                            <option>Korean</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 main-content-label">Name</div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">User Name</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="User Name"
                                            value="Petey Cruiser">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">First Name</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="First Name" value="Petey">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">last Name</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Last Name" value="Pechon">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Nick Name</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Nick Name" value="Petey">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Designation</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Designation"
                                            value="Web Designer">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 main-content-label">Contact Info</div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Email<i>(required)</i></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Email"
                                            value="info@Valex.in">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Website</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Website" value="@spruko.w">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Phone</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="phone number"
                                            value="+245 354 654">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Address</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="example-textarea-input" rows="2"
                                            placeholder="Address">San Francisco, CA</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 main-content-label">Social Info</div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Twitter</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="twitter"
                                            value="twitter.com/spruko.me">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Facebook</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="facebook"
                                            value="https://www.facebook.com/Redash">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Google+</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="google" value="spruko.com">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Linked in</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="linkedin"
                                            value="linkedin.com/in/spruko">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Github</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="github"
                                            value="github.com/sprukos">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 main-content-label">About Yourself</div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Biographical Info</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="example-textarea-input" rows="4"
                                            placeholder="">pleasure rationally encounter but because pursue consequences that are extremely painful.occur in which toil and pain can procure him some great pleasure..</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 main-content-label">Email Preferences</div>
                            <div class="form-group mb-0">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Verified User</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="custom-controls-stacked">
                                            <label class="me-1"><input checked="" type="checkbox"
                                                    class="form-check-input mb-2 me-1"><span> Accept to receive post or
                                                    page notification emails</span></label>
                                            <label class=""><input checked="" type="checkbox"
                                                    class="form-check-input mb-2 me-1"><span> Accept to receive email
                                                    sent to multiple recipients</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Update Profile</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End::row-1 -->
    </div>
<!-- End::app-content -->
@endsection