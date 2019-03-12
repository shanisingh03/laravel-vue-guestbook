<template>
    <div class="container-fluid">

        <div class="row mt-5" v-if="isDataLoaded">
          <div class="col-md-12">
              <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-info-active">
                <h3 class="widget-user-username">{{user.name}}</h3>
                <!-- <h5 class="widget-user-desc">Founder &amp; CEO</h5> -->
              </div>
              <div class="widget-user-image">
                <img class="img-circle elevation-2" :src="getProfilehoto()" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">{{contacts.length}}</h5>
                      <span class="description-text">Contacts</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">{{contacts.length}}</h5>
                      <span class="description-text">Emails</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">0</h5>
                      <span class="description-text">Fav Contacts</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">My Profile Info</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form">
                <div class="card-body">
                <!-- Email -->
                <div class="form-group">
                    <input v-model="form.email" type="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Enter email" name="email">
                    <has-error :form="form" field="email"></has-error>
                </div>
                <!-- Name -->
                <div class="form-group">
                    <input type="text" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Name" name="name">
                    <has-error :form="form" field="name"></has-error>
                </div>

                <!-- Photo -->
                <div class="form-group">
                    <label for="photo" class="col-sm-2 control-lebel">Photo</label>
                    <div class="col-sm-6">
                        <input type="file" @change="updatePhoto" name="photo">
                    </div>

                </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" @click.prevent="updateProfileInfo" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
    </div>
</template>

<script>
    export default {
        props : ['user'],
        data() {
            return {
                isDataLoaded: false,
                profileData:[],
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    photo: '',

                }),
                contacts:[],
            }
        },
        methods:{
            getProfilehoto(){
                let mypic = (this.form.photo.length > 200) ? this.form.photo :"img/profile/"+this.form.photo;
                return  mypic;
            },
            // Update Photo
            updatePhoto(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                // If Image Size Is greater Than 2MB 2111775 bits
                if (file.size < 2111775) {
                   reader.onloadend = (file) => {
                        this.form.photo = reader.result;
                    }

                    reader.readAsDataURL(file);
                }else{
                    console.log(file);
                    toast.fire({
                        type: 'error',
                        title: 'Image Must Be Less than 2MB.'
                    });
                }

            },
            // Update Profile Info
            updateProfileInfo(){
                this.$Progress.start();
                this.form.post('api/profile/update')
                         .then((data)=>{
                             if(data.status == 200){
                                // Fire An Event To refresh Data
                                Fire.$emit('AfterUpdateProfile');
                                // Toaster Message
                                toast.fire({
                                    type: 'success',
                                    title: 'Profile Updated Successfully.'
                                });
                                // Finish Progress Bar
                                this.$Progress.finish();
                                this.getMyProfile();

                            }else{
                                toast.fire({
                                    type: 'error',
                                    title: 'Fail To Update Profile.'
                                });
                                // Fail Progress Bar
                                this.$Progress.fail();
                            }
                         })
                         .catch(()=>{
                             // Fail Progress Bar
                            this.$Progress.fail();

                            // Alert Message
                            toast.fire({
                                type: 'error',
                                title: 'Fail To Update Profile.'
                            });
                         });
            },
            getMyProfile(){
                axios.get("api/myprofile").then(
                    ({data}) =>{
                        // console.log(data);
                        this.profileData = data.profile;
                        this.contacts = data.contacts;
                        this.isDataLoaded = true;
                        this.form.fill(this.profileData);
                        }
                    );
            }

        },
        mounted() {
            this.getMyProfile();
            // Update Profile Event
            Fire.$on('AfterCAfterUpdateProfilereateContact',()=>{
                this.getMyProfile();
            });
            console.log(' Profile Component mounted.')
        }
    }
</script>
