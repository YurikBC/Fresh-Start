<template>
     <div>
    <div class="label">
   <div class="container">
    <form class="singin-form">
      <div class="auth-form-body">

            <div class="try">
            <h2>Sign In</h2>
            </div>
            <br>
        <table class="singin-table">
          <tr>
            <td>
              <label for="login_field">Email address:</label>
            </td>
          </tr>
            <tr><input v-model="user.mail" autofocus="autofocus" class="form-control input-block" id="login_field" name="login" tabindex="1" type="email"/></tr>
            <span v-show="isErrorMail" >{{ emailMsg }}</span>
            <br>
          <tr>
            <td><label for="password">Password: <a class="label-link">Forgot password?</a></label></td>
          </tr>
            <tr><input v-model="user.password" class="form-control form-control input-block" id="password" name="password" tabindex="2" type="password"/></tr>
            <span class="error_control" v-show="isErrorPswd">{{ passwordMsg }}</span>
            <br>
            <br>
            <tr>
                <input class="check" type="checkbox" v-model="user.remember">
                <label class="label_c" @click.prevent="user.remember = !user.remember">Remember me</label>
            </tr>
          <tr class='tr'>
            <input class="btn" data-disable-with="Signing in…" name="commit" tabindex="2" type="submit" value="Sign in" @click.prevent="onSingIn"/>
          </tr>
        </table>
      </div>
    </form>
    </div>
</div>
    </div>
</template>

<script>
export default {
  name: 'singIn',
  data () {
    return {
        user:{
            mail: '',
            password:'',
            remember: false
        },
        showSignOut: false,
        showForm: false,
        isErrorPswd: true,
        isErrorMail: true,
        emailMsg: '',
        passwordMsg: '',

    }
  },
    watch: {
        'user.mail': function (value) {
            console.log(value);
            if (this.requiredField(value, 'emailMsg')) {
                this.valid_email(value, 'emailMsg');
            }
        },
        'user.password': function (value) {
            console.log(value);
            if (this.requiredField(value, 'passwordMsg'))
                this.check_password_length(value, 'passwordMsg', 16);
        },
    },
    methods:{
        requiredField(value, msg){
            if(value !== ''){
                this[msg] = '';
                return true;
            }
            else{
                this[msg]='Field is required';
                return false;
            }
        },


        valid_email(email, msg) {
            let emailRE = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (emailRE.test(this.user.mail)) {
                this[msg] = '';
                return true;
            } else {
                this[msg] = 'Keep typing...waiting for a valid email';
                return false;
            }
        },
        check_password_length(value, msg, total) {
            let length = value.length;
            let sum = 0;
            let display;

            sum = (total - length);

            switch (sum) {
                case 0:
                    display = '';
                    break;
                case 1:
                    display = 'Keep going - just need ' + sum + ' more character.';
                    break;
                default:
                    display = 'Keep going - just need ' + sum + ' more characters';
            }

            if (length >= total) {
                this[msg] = '';
                return true;
            } else {
                this[msg] = display;
                return false;
            }

        },

        clickBtn1() {
            this.showForm = !this.showForm;

        },

        onSingIn(){
            if(this.user.mail === '' || this.user.password === '')
            {
                alert('All fields are required!!!')

            }
            else{
                axios.post('http://jsonplaceholder.typicode.com/posts', {
                    mail: this.user.mail,
                    password: this.user.password,
                    remember: this.user.remember
                }).then(function (response) {
                    console.log(response);
                })
                    .catch(function (error) {
                        console.log(error);
                    });

                if (this.showForm) {
                    document.getElementById('btn1').value = 'Profile';
                    this.showSignOut = true;
                    this.showForm = false;
                } else {
                    document.getElementById('btn1').value = 'Sing in';
                    this.showSignOut = false;
                }
            }

        },

        onSingOut(){
            document.getElementById('btn1').value = 'Sing in';
            this.showSignOut = false;


        }


}



}
</script>


<style scoped>
 .container {

  text-align: left;
  color: white;
  margin-top: 30px;
     padding: 20px 50px 50px 50px;
     color: white;
     width: 550px;
     border-radius: 10px;
     box-shadow: 2px 2px 2px 1px rgba(0,0,0,0.15);
     background-image: url(/src/img/blue.jpg);
     background-position: center;
     background-attachment: fixed;
}

    .label {
        width: 550px;
        height: 200px;
        margin: 30px auto;
        text-align: center;
        border-radius: 20px;
        color: red;
        background-color: #EF7F35;
        box-shadow: 1px 2px 4px 2px rgba(0,0,0,0.15);
        padding-left: 10px;
    }

h1, h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #ff7f2b;
}

    label {
        color: white;
        font-size: 15px;

    }
        input{

        background-color: white;
        border-bottom-color: grey;
        border-radius: 10px;
        color: black;
        padding-left: 5px;
        border: none!important;
        padding-left: 5px;
        position: relative;
        height: 35px;
        box-shadow: 1px 2px 4px 2px rgba(0,0,0,0.15);
        border-radius: 5px;
        color: black;
        background-color: #fff;
        margin-bottom: 0px;
        outline: 0!important;
    }
    .btn {
        background-color: #ff7f2b;
        margin: auto;
        width: 100px;
        height: 40px;
    }

                .input:focus{
                border-color: inherit;
                -webkit-box-shadow: none;
                box-shadow: none;
            }

    .btn {
        color: white;
        background-color: #EF7F35;
        width: 200px;
        border-radius: 5px;
        height: 40px;
        padding-left: 20px;
        border: none;
        box-shadow: 0px 0px 3px 2px rgba(0,0,0,0.15);
        cursor: pointer;
        text-align: center;

    }

    .btn:active {
        transform: translateY(4)
    }

    .btn:hover{
        box-shadow: 0px 0px 3px 2px rgba(0,0,0,0.3);
    }

    .tr {
        text-align: center;
    }
        .try {
        text-align: center;
        margin-right: 10px;
            margin-top:0px;
    }

    span {
        color: white;
        font-style: italic;
        opacity: 1;
    }

    .top {
        margin-top: 40px;
    }

    .check{
    margin-bottom: 10px;
    }
    .label_c{
      margin-top: -20px;
      margin-bottom: 25px;
    }


</style>
