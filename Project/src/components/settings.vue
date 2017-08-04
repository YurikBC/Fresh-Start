<template>
    <div>

        <section class="divSettings">
        <div class="container">
            <h1>Settings</h1>
            <div class="boxOfInputs">
                <div class="inputs" @keyup.enter="updateUser(user)">
                    Name:<div><input class="inputsInSettings" type="text" v-model="user.userName" placeholder="Name"></div>
                    Number:<div><input class="inputsInSettings" type="text" v-model="user.number" placeholder="Number"></div>
                    City:<div><input class="inputsInSettings" type="text" v-model="user.city" placeholder="City"></div>
                    Password:<div><input class="inputsInSettings" type="password" v-model="user.password" placeholder="Password" ></div>
                </div>
                <div></div>
            </div>
            <div id="confirm">
                <button @click="updateUser(user)" id="buttonConfirm">Confirm settings</button>
            </div>
            </div>
        </section>
    </div>


</template>

<script>
    import myheader from './myheader.vue'
    export default {
        name: 'settings',
        data() {
            return {
                user: {
                    userName: "",
                    number: "",
                    city: "",
                    password: "****"
                }
            }
        },
        components:{myheader},
        methods: {
            updateUser(){
                //this.$store.dispatch('UpdateUser', this.user);
            }
        }
    }
</script>

<style>
    .divSettings {
        background-color: white;
        height: 89.5vh;padding-top: 30px;
    }

    .container{
        padding-top: 30px;
        background-color: #EF7F35;
        background-position: center;
        background-attachment: fixed;
        padding-bottom: 40px;
        border-radius: 15px;
        width: 550px;
        margin: auto;

    }
    h1{
        display: flex;
        justify-content: center;
        margin: 0;
    }
    .boxOfInputs {
        display: flex;
        justify-content: space-around;
        height: 40vh;
        align-items: flex-end;
        margin: 0 100px 0 100px;
    }
    .inputs {
        flex-direction: column;
        align-items: flex-end;
    }
    .inputs input {
        width: 400px;
        border: 2px forestgreen solid;
    }

    #buttonConfirm {
        display: inline-block;
        margin-top: 20px;
        background-color: #039BE5;
        padding: 15px 5px;
        border: none;
        width: 170px!important;
        border-radius: 10px;
        color: snow;
        margin-left:  200px;
        
    }
    #buttonConfirm:hover { background: rgb(127, 207, 255); }
    #buttonConfirm:active { background: rgb(94, 126, 152); }

    input {
        border: none!important;
        outline: none!important;
        color: white;
        background-color: white!important;
        border-bottom-color: grey;
        border-radius: 10px;
        color: black;
        padding-left: 5px;
        border: none!important; 
        padding-left: 10px!important;
        position: relative;
        height: 35px;
        box-shadow: 1px 2px 4px 2px rgba(0,0,0,0.15)!important;
        border-radius: 10px!important;
        color: black;
        background-color: #fff;
        margin-bottom: 0px;
        outline: 0!important;

    }


</style>