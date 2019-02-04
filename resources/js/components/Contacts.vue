<template>
    <div class="container-fluid">
        <div class="row mt-5">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">My Contacts({{contacts.length}})</h3>

                <div class="card-tools">
                  <button class="btn btn-success" @click="openNewContactModal">
                      Add
                      <i class="fas fa-user-plus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>SR.No</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>About</th>
                    <th>Save At</th>
                    <th>Is Fav</th>
                    <th>Action</th>
                  </tr>
                  <tr v-for="(contact , index) in contacts" :key="contact.id">
                    <td>{{index + 1}}</td>
                    <td>{{contact.name}}</td>
                    <td>{{contact.mobile}}</td>
                    <td>{{contact.email}}</td>
                    <td>{{contact.comment}}</td>
                    <td>{{contact.created_at | readableDate}}</td>
                    <td><span class="tag tag-success">{{contact.is_fav == 1 ? 'Yes' : 'NO' | upText}}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" @click="openEditContactModal(contact)">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button class="btn btn-sm btn-danger" v-on:click="deleteContact(contact.id)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNewContactModal" tabindex="-1" role="dialog" aria-labelledby="addNewContactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewContactModalLabel">
                    <span v-if="editMode">Edit</span> <span v-else> Add New</span> Contact
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="editMode ? updateContact() : createContact()" @keydown="form.onKeydown($event)">
                <div class="modal-body">
                        <!-- Name -->
                        <div class="form-group">
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Full Name">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <!-- Email -->
                        <div class="form-group">
                            <input v-model="form.email" type="email" name="email"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Email Of User">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <!-- Mobile Number -->
                        <div class="form-group">
                            <input v-model="form.mobile" type="text" name="mobile"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('mobile') }" placeholder="Mobile Number">
                            <has-error :form="form" field="mobile"></has-error>
                        </div>
                        <!-- PHOTO -->


                        <!-- Comment -->
                        <div class="form-group">
                            <textarea v-model="form.comment" name="comment"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('comment') }" placeholder="About Contact (Optional)"></textarea>
                            <has-error :form="form" field="comment"></has-error>
                        </div>
                        <!-- IS Fav -->
                        <div class="form-group">
                            <label>Is Favorite </label>
                            <input v-model="form.is_fav" type="checkbox" name="is_fav" :class="{ 'is-invalid': form.errors.has('is_fav') }">
                            <has-error :form="form" field="is_fav"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.user_id" type="hidden" name="user_id" :class="{ 'is-invalid': form.errors.has('user_id') }">
                            <has-error :form="form" field="user_id"></has-error>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-success">
                        <span v-if="editMode"><i class="fas fa-user-edit"></i> Update</span>
                        <span v-else><i class="fas fa-save"></i> Save</span>
                    </button>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        props : ['user'],
        data(){
            return {
                editMode:false,
                contacts:[],
                form: new Form({
                    id: '',
                    name: '',
                    mobile: '',
                    email: '',
                    is_fav: '',
                    photo: '',
                    comment: '',
                    user_id: this.user.id,

                })
            }
        },
        methods:{
            // Open New Contact Modal
            openNewContactModal(){
                this.editMode = false;
                this.form.reset();
                $('#addNewContactModal').modal('show');
            },
            //Open Edit Contact Modal
            openEditContactModal(contact){
                this.editMode = true;
                // this.form.reset();
                this.form.fill(contact);
                $('#addNewContactModal').modal('show');
            },
            // Create Contact
            createContact(){
                this.$Progress.start();
                this.form.post('api/contact')
                    .then((data)=>{
                        if(data.status == 201){
                            // Fire An Event To refresh Data
                            Fire.$emit('AfterCreateContact');
                            // Toaster Message
                            toast.fire({
                                type: 'success',
                                title: 'Contact Created Successfully.'
                            });
                            // Finish Progress Bar
                            this.$Progress.finish();

                            // Hide The Modal
                            $('#addNewContactModal').modal('hide');
                        }else{
                            toast.fire({
                                type: 'error',
                                title: 'Fail To Create Contact.'
                            });
                            // Finish Progress Bar
                            this.$Progress.finish();
                        }

                    })
                    .catch(()=>{
                        // Finish Progress Bar
                        this.$Progress.finish();

                        // Alert Message
                        toast.fire({
                            type: 'error',
                            title: 'Fail To Create Contact.'
                        });
                    }
                );

            },
            // Get List Of Contacts
            getMyContacts(){
                this.$Progress.start();
                axios.get("api/contact?user_id="+this.user.id).then(
                    ({data}) =>{
                        this.contacts = data.data;
                        this.$Progress.finish();
                        }
                    );

            },
            // Delete Contact
            deleteContact(id){
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                       if(result.value){
                            // Send Delete Request To Server
                            this.form.delete('api/contact/'+id).then((data)=>{
                                // console.log(data);
                                if(data.status == 200){
                                    // Fire An Event To refresh Data
                                    Fire.$emit('AfterCreateContact');

                                    // Alert
                                    swal.fire(
                                    'Deleted!',
                                    data.data.message,
                                    'success'
                                    );
                                }else{
                                    // Error Alert
                                    swal.fire(
                                    'Error!',
                                    'Fail To Delete Contact.',
                                    'error'
                                    )
                                }
                            });
                       }
                    })
                    .catch(()=>{
                        swal.fire(
                            'Error!',
                            'Something Went Wrong.',
                            'error'
                            );
                    });
            },
            // Update Contact
            updateContact(){
                this.$Progress.start();
                this.form.put('api/contact/'+this.form.id)
                .then((data)=>{
                    // Fire An Event To refresh Data
                    Fire.$emit('AfterCreateContact');
                    // Toaster Message
                    toast.fire({
                        type: 'success',
                        title: 'Contact Updated Successfully.'
                    });
                    // Finish Progress Bar
                    this.$Progress.finish();

                    // Hide The Modal
                    $('#addNewContactModal').modal('hide');
                })
                .catch(()=>{
                    toast.fire({
                        type: 'error',
                        title: 'Fail To Update Contact.'
                    });
                    // Finish Progress Bar
                    this.$Progress.finish();
                });
            }

        },
        mounted() {
            this.getMyContacts();
            Fire.$on('AfterCreateContact',()=>{
                this.getMyContacts();
            });
            console.log('Contact Component Success!.')
        }
    }
</script>
