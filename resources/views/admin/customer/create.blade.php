@extends('admin.admin_dashboard')
@section('admin')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <h6 class="mb-0 text-uppercase">Customer Form Details</h6>
            <hr />
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-5">
                    <div class="card-title d-flex align-items-center">
                        <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                        </div>
                        <h5 class="mb-0 text-primary">Customer Registration</h5>
                    </div>
                    <hr>       
                    <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label for="inputFirstName" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="inputFirstName" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputLastName" class="form-label">Surname</label>
                            <input type="text" name="surname" class="form-control" id="inputLastName" value="{{ old('surname') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="inputEmail" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputPhoneNumber" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" id="inputPhoneNumber" value="{{ old('phone_number') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputProfileImage" class="form-label">User Profile Image</label>
                            <input type="file" name="profile_image" class="form-control" id="inputProfileImage" onchange="previewImage(event)">
                        </div>
                        <div class="col-md-6">
                            <img id="showImage" src="{{ (!empty($customer->profile_image)) ? url('upload/customer_upload/',$customer->profile_image) : url('upload/No_Image_Available.jpg') }}" alt="customer"  style="height:100px; width:100px;">
                        </div>
                        <div class="col-md-6">
                            <label for="inputJoiningDate" class="form-label">Joining Date</label>
                            <input type="date" name="joining_date" class="form-control" id="inputJoiningDate" value="{{ old('joining_date') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputGender" class="form-label">Gender</label><br>
                            <input type="radio" name="gender" value="male" id="genderMale" {{ old('gender') == 'male' ? 'checked' : '' }}> 
                            <label for="genderMale">Male</label>
                            <input type="radio" name="gender" value="female" id="genderFemale" {{ old('gender') == 'female' ? 'checked' : '' }}> 
                            <label for="genderFemale">Female</label>
                            <input type="radio" name="gender" value="other" id="genderOther" {{ old('gender') == 'other' ? 'checked' : '' }}> 
                            <label for="genderOther">Other</label>
                        </div>
                        <div class="col-md-6">
                            <label for="inputState" class="form-label">State</label>
                            <select name="state" id="inputState" class="form-select">
                                <option selected>Choose...</option>
                                <option value="Maharashtra">Maharashtra</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Kerala">Kerala</option>
                                <option value="Karnataka">Karnataka</option>
                                <!-- Add other states as needed -->
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="inputFile" class="form-label">File</label>
                            <input type="file" name="file" class="form-control" id="inputFile" onchange="previewImages(event)">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-5">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function previewImage(event) {
        var input = event.target;
        var reader = new FileReader();

        reader.onload = function () {
            var dataURL = reader.result;
            var output = document.getElementById('showImage');
            output.src = dataURL;
        };

        reader.readAsDataURL(input.files[0]);
    }
</script>

