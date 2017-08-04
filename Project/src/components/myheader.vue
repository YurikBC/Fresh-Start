<template>
           <header>
            <div class="header">
                <ul>
                   
                   <li>
                      <router-link to="/">
                       <img src="/src/img/exposit3.png" alt="">
                       </router-link>
                   </li>
                    <li>
                        <router-link to="/">
                            <p>Главная</p>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/contacts">
                            <p>Контакты</p>
                        </router-link>
                    </li>                 
                <div class="right_container">
                   
                                         <li >
                       <div class="dropdown">
       
                           <p><dropDown></dropDown></p>
               
                       </div>
                    </li>
                    <li>
                       <li>
                       <router-link to="/singIn">
                          <div class="reg">
                               <p>Войти</p>
                           </div>
                        </router-link>
                    </li>  

                    <li>
                       <router-link to="/registration">
                          <div class="reg">
                               <p >Регистрация</p>
                           </div>
                        </router-link>
                    </li>  
                 


                </div>    
                </ul>
            </div>



        </header>
</template>




<script>
import dropDown from './dropDown.vue'
export default {
    name: 'myheader',
    data(){
            return{
                show: false
            };
        },
    components: { 
        dropDown
    }
}



</script>

<style scoped>
header {
    background-color: #039BE5 !important;
    font-size: 20px;
    font-weight: 300;
    padding-left: 15px;
    margin-top: 0px;
    min-height: 80px;

    
}

header p{
    color: white;
}

header li {
    display: inline-block;
    margin-left: 15px;
}

header ul {
    padding: 0px;
    margin: 0px;
}
    
    .reg > p {
        font-size: 20px;
        background-color: #ef7f35;
        padding: .5em 1.3em;
        border-radius: 5px;
       

    }
    
    .dropdown {
       
        
    }
    
    .right_container{
        display: inline-block;
        float: right;
        margin-right: 20px;
       
        
    }
    
    img {
        height: 50px;
        margin-right: 30px;
    }

        a:hover {
        text-decoration: none !important;
        
    }

</style>