
<!--Website: wwww.codingdung.com-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodingDung | Profile Template</title>
    <link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Donor Profile
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Change password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-info">Info</a>
                       
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <div class="card-body media align-items-center">
                                <img src="profile.png" alt class="d-block ui-w-80">
                                <div class="media-body ml-4">
                                    <label class=" upload">
                                        Upload new photo
                                        <input type="file" class="account-settings-fileinput">
                                    </label> &nbsp;
                                    <button type="button" class="btn btn-default md-btn-flat" >Reset</button>
                                    <div class="text-light small mt-1">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control mb-1" value="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">NIC</label>
                                    <input type="text" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="text" class="form-control" value="">
                                </div>
                               
                                <div class="form-group">
                                    <label class="form-label">Blood Type</label>
                                    <input type="text" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <input type="text" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Health status</label>
                                    <textarea class="form-control"
                                        rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Current password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Repeat new password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary">Save changes</button>&nbsp;
                                    <button type="button" class="btn btn-default">Cancel</button>
                                </div>
                            </div>
                            
                        </div>
                        <div class="tab-pane fade" id="account-info">
                            <div class="card-body pb-2">
                               
                                
                                <div class="form-group">
                                    <label class="form-label">District</label>
                                    <select class="custom-select">
                                        <option>Select</option>
                                        <option>Badulla</option>
                                        <option>Monaragala</option>
                                        
                                    </select><br>
                                    
                                </div>
                            </div>
                            
                            <div class="card-body pb-2">
                                
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">E-mail</label>
                                    <input type="text" class="form-control mb-1" value="">
                                    <div class="alert alert-warning mt-3">
                                        Your email is not confirmed. Please check your inbox.<br>
                                        <a href="javascript:void(0)">Resend confirmation</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Profession</label>
                                    <input type="text" class="form-control" value="">
                                </div>

                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-primary">Save changes</button>&nbsp;
                                    <button type="button" class="btn btn-default">Cancel</button>
                                </div>
                            </div>
                            
                          <h5 class="form-label" >Awards</h5><br>
                                <div class="crd-container"  class="form-group">
                                    
                                    <div class="crd" >
                                        <img src="example-image1.jpg" alt="Example Image 1">
                                        <div class="crd-content">
                                            <h2>Silver Badge</h2>
                                            <p>Congatulations! You have donated blood more than 10 times.</p>
                                        </div>
                                    </div>
                                    <div class="crd">
                                        <img src="example-image2.jpg" alt="Example Image 2">
                                        <div class="crd-content">
                                            <h2>Golden Badge</h2>
                                            <p>Congatulations! You have donated blood more than 25 times.</p>
                                        </div>
                                    </div>
                                    <div class="crd">
                                        <img src="example-image3.jpg" alt="Example Image 3">
                                        <div class="crd-content">
                                            <h2>Platinum Badge</h2>
                                            <p>Congatulations! You have donated blood more than 50 times.</p>
                                        </div>
                                    </div>
                                </div>
                            
                            
                        </div>
                        
                </div>
               
            </div>
            
        </div>
        
    </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

    </script>
</body>

</html>