<template>
    <div class="container-fluid">
        <div class="row mt-5" v-if="isDataLoaded">
          <div class="col-lg-4 col-6">
            <!-- small card -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{dashDataContacts.length}}</h3>

                <p>Total Contacts</p>
              </div>
              <div class="icon">
                <i class="fas fa-phone-square"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small card -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{dashDataContactsFav.length}}</h3>

                <p>Fav Contacts</p>
              </div>
              <div class="icon">
                <i class="fas fa-heart"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small card -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{dashDataContacts.length}}</h3>

                <p>Email Ids</p>
              </div>
              <div class="icon">
                <i class="fas fa-at"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
        </div>
    </div>
</template>

<script>
    export default {
        props : ['user'],
        data(){
            return {
                isDataLoaded: false,
                dashDataContacts:[],
                dashDataFavContacts:[],
            }
        },
        methods:{
            // Get List Of Contacts
            getDashData(){
                this.$Progress.start();
                axios.get("api/dashboard?user_id="+this.user.id).then(
                    ({data}) =>{
                        // this.dashData = data;
                        this.dashDataContacts = data.contacts;
                        this.dashDataContactsFav = data.fav_contacts;
                        this.isDataLoaded = true;
                        this.$Progress.finish();
                        }
                    );

            }
        },
        mounted() {
            this.getDashData();
            console.log('Dashborad Component mounted.')
        }
    }
</script>
