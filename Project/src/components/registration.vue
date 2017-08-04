<template>           
      <div class = "container">
        <div class="registration_cont">
           <br>
            <div class="try">
            <h2>Registration</h2>
            </div>
            <br>
            <table>
                    <tr>
                       <div class="try">
                        <label class="lbl"><p>NickName:</p></label></div>
                   
                    </tr>
                    <tr>
                        <div class="login">
                            <input type="text" maxlength="16" name="NickName" v-model="newUser.name" placeholder="Login" > 
                            <br>
                            <span class="error error_control" v-show="isErrorName">{{ userMsg  }}</span>
                         </div>
                    </tr>
                    
                       
                        
                  
                    <br>
                        
                    <tr>
                        <div class="try">
                            <label class="lbl"><p>Email:</p></label>
                        </div>
                    </tr>    
                    <tr>
                           <div class="email">
                               <input type="email" name="Email" v-model="newUser.email" placeholder="Email"><br>
                               <span v-show="isErrorMail" >{{ emailMsg }}</span>
                           </div>
                    </tr>
                    <br>
              
                <tr>
                    <div class="try">
                        <label class="lbl"><p>City:</p></label>
                    </div>
                            <div class="select">
                            <select name="city" v-model="newUser.city">
                                <option value="" disabled selected hidden>Select city</option>
                                <option>Брест</option>
                                <option>Витебск</option>
                                <option>Гомель</option>
                                <option>Гродно</option>
                                <option>Минск</option>
                            </select>
                        </div>   
                </tr>
              <BR></BR>
                    <tr>
                       <div class="try">
                        <label class="lbl"><p>Password:</p></label> 
    </div>
                    </tr>
                    <tr>
                        <div class="password">
                            <input type="password" name="Password" v-model="newUser.password" placeholder="Password" maxlength="16" >
                        </div>
                            <br>
                        <span class="error_control" v-show="error_msg">
                            {{ password_msg }}
                        </span>
                    </tr>
                
                <tr>
                       <div class="try">
                        <label class="lbl"><p>Confirm password:</p></label>
    </div> 
                   </tr>
                    <tr>
                        <div class="password conf">
                            <input type="password" name="Password2" v-model="newUser.password2" placeholder="Repeat password" maxlength="16">
                            <br>
                            <span class="error_control" v-show="error_msg">
                                {{ password2_msg }}
                            </span>
                        </div>
                    </tr>
                     <br>
                     <br>
                
                <tr class="checkbox_tr">
                    <div class="checkbox">
                            <p>I want to receive newsletters about new vacancies</p><input type="checkbox" class = "chbox" id="chbox" v-model="newUser.mailing">
                    </div>
                    </tr>
        
        <br>
                <tr>
                    <button :class="{active: disable_btn}" :disabled="disable_btn" @click.prevent="on_signup">
                        SING UP
                    </button>   
                </tr>
   
            </table>     
        </div>
    </div>
    
                    
</template>

<script>
    export default {
        name: "registration",
        data() {
            return {
                newUser: {
                    name: '',
                    email: '',
                    city: '',
                    password: '',
                    password2: '',
                    mailing: false,
                },
                isErrorName: true,
                isErrorMail: true,
                isErrorPswd: true,
                userMsg: '',
                emailMsg: '',
                passwordMsg: '',
                passwordMsg2: '',
                disableBtn: true,
                isActive: true,
                hasError: false
            }
        },
        watch: {
            'newUser.email': function(value) {
                console.log(value);
                if(this.requiredField(value,'emailMsg')){
                    this.valid_email(value, 'emailMsg');
                }
            },
            'newUser.password': function (value) {
                console.log(value);
                if(this.requiredField(value, 'passwordMsg'))
                    if (this.check_password_length(value, 'passwordMsg', 16)) {
                        this.check_passwords_match();
                        console.log(this.disableBtn);
                    }
            },
            'newUser.password2': function (value) {
                console.log(value);
                if(this.requiredField(value, 'passwordMsg2'))
                    if (this.check_password_length(value, 'passwordMsg2', 16)) {
                        this.check_passwords_match();
                        console.log(this.disableBtn);
                        console.log(this.isErrorName);
                    }
            },
            'newUser.name': function (value) {
                this.requiredField(value,'userMsg');
            }
        },
        
        methods: {
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
                if (emailRE.test(this.newUser.email)) {
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
            check_passwords_match() {
                if (this.newUser.password.length > 15 && this.newUser.password2.length > 15) {
                    if (this.newUser.password2 !== this.newUser.password) {
                        console.log(this.newUser.password);
                        this.passwordMsg2  = 'Passwords do not match';
                        this.disableBtn = true;
                        return true;
                    } else {
                        this.password2_msg = '';
                        this.disableBtn = false;
                        return false;
                    }
                }
            },
            //            isValid(){
            //                if(this.newUser.name !== '' && this.newUser.email !== '' && this.newUser.city !== '' && this.newUser.password !== '' && this.newUser.password2 !== '')
            //                {
            //                    this.error_msg = true;
            //                    this.user_msg = 'NOO';
            //                }
            //                else {
            //                    this.error_msg = false;
            //                    this.user_msg = '';
            //                }
            //            },
            check_requered_fields() {
                this.disableBtn = this.newUser.name !== '' && this.newUser.email !== '' && this.newUser.city !== '' && this.newUser.password !== '' && this.newUser.password2 !== '';
            },
            on_signup(){
                if(this.newUser.name !== '' && this.newUser.email !== '' && this.newUser.city !== '' && this.newUser.password !== '' && this.newUser.password2 !== '') {
                    console.log(this.newUser.name);
                    console.log(this.newUser.email);
                    console.log(this.newUser.city);
                    console.log(this.newUser.password);
                    console.log(this.newUser.mailing);
                    axios.post('http://jsonplaceholder.typicode.com/posts', {
                        name: this.newUser.name,
                        email: this.newUser.email,
                        city: this.newUser.city,
                        password: this.newUser.password,
                        mailing: this.newUser.mailing
                    }).then(function (response) {
                        console.log(response);
                    })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
                else{
                    console.log('не все поля заполнены');
                }
            }
        }
    }
</script>

<style scoped>
<style scoped>
    * {
        background-color: #039BE5;
        color: white;
       
        margin: auto;
        
    }

    table{
        table-layout: fixed;
        margin: auto;
        width: 40%;
        width: 550px;

    }
    p {
        color: black
    }


    .registration_cont {
        padding-top: 1px;
        background-color: #088ecc;
        background-image: url(/src/img/blue.jpg);
        background-position: center;
        background-attachment: fixed;
        padding-bottom: 40px;
        border-radius: 15px;
        width: 550px;
        margin: auto;
        
        
            }

    .container {
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

    tr{
        line-height: 0px;

    }
    

    
    input {
        border-radius: 5px;
        width: 250px;
        margin-top: 0px;
    }
    
    
    
    select {
        width: 250px;
        text-align: center;
        border-radius: 5px;
        height: 26px;
    }

    button {
        color: white;
        background-color: #EF7F35;
        width: 300px;
        border-radius: 5px;
        height: 40px;
        margin-left: 20px;
        border: none;
        box-shadow: 0px 0px 3px 2px rgba(0,0,0,0.15);
        cursor: pointer;
        text-align: center;
        
    }
    
    button:active {
        transform: rotateY(2px);
    }
    
    button:hover{
        box-shadow: 0px 0px 3px 2px rgba(0,0,0,0.5);
    }
    
        .try{
                text-align: left;
            }
 
    .checkbox{
        display: block;
        margin-bottom: 10px;
        width: 480px;
        padding-bottom: 10px;
        vertical-align: top;
        position: relative;
            
        
    }
           
    .try{
        margin: auto;
        max-width: 350px;
        padding-bottom: 5px;
        margin-bottom: -8px;
        font-size: 16px;
            }
            
        .login, .email, .select, .password{
              margin: auto;
        max-width: 350px;
            text-align: left;
            }   
            
            input, select {
                width: 350px;
                border: none; 
                padding-left: 5px;
                height: 35px;
                box-shadow: 1px 2px 4px 2px rgba(0,0,0,0.15);
                border-radius: 5px;
                color: black;
                background-color: #fff;
                outline: 0;
                
                
                
            }
            
           input:focus, select:focus, input:active, select:active{
                box-shadow: 0px 0px 3px 1.5px firebrick;
               
            }
            input:hover, select:hover {
                box-shadow: 0px 0px 4px 2px rgba(0,0,0,0.50);
            }
            
            .input:focus{
                border-color: inherit;
                -webkit-box-shadow: none;
                box-shadow: none;
            }
            
            select {
                width: 355px;
                display: inline-block;
                height: 35px;
                color: #D1D1D1;
                 padding-left: 2px;
                 padding-right: 2px;
                
            }
            select:checked, select:focus, select:active {
                color: black;
                 box-shadow: none;
        }
            
            .error_control {
                text-align: left;
            }
            
            .checkbox {
               max-width: 360px; 
                text-align: left;
                margin: auto;
                margin-top: 25px;
                
                
            }
            .checkbox input {
                width: 14px;
                vertical-align: bottom;
                margin-bottom: 15px;
                border-radius: 0;
                opacity: 1!important;
                position: absolute;
                left: 350px;
                top: -3px;
                background-color: none;
                box-shadow: none;
            
               
            }
    
         #chbox:focus, #chbox:focus, #chbox:active, #chbox:active{
                box-shadow: none;
               
            }
            

        
            option{
                
                color: black;
            }
            
            h2 {
                text-align: center;
                width: 350px;
            }
    p {
        color: white;
        font-size: 17px;
    }
    
    span {
        position: absolute;
        font-size: 14px;
        color: white;
        font-style: italic;
        opacity: 0.9;
    }
    input{
        margin-bottom: 0px;
        border: none!important; 

       
    }


    br{
        display: block;
     margin: 10px 0;
        
        content: " ";
    }
    
    
    select {
        display: inline-block;
    }

    td{
        width: 250px;
        border: none;
        
    }
    
    .left_col {
        width: 130px;
        border-bottom: 1px solid white;
    }
    

                .checkbox p {
                display: inline-block;
                padding-right: 10px;
                
            }
    h2{
        color: white;
    }
    .checkbox p{
        font-size: 15px;
    }
</style>
