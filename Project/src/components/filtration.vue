<template>
   <div class="filt">
   <div class="filt_title">
    <h5>Фильтрация</h5>
    </div>
    
    <div class="filt_by_city">
        <p>Город:</p>
    
<div class="bloc">
   <select type="text" name="city" class="select_by_city" size="6" v-model="city">        
        <option> Брест</option>
        <option> Витебск</option>
        <option> Гомель</option>
        <option> Гродно</option>
        <option> Минск</option>
   </select>
</div>
    </div>
    
<div class="filt_by_employment" >
    <p>Тип занятости:</p>
<div class="bloc">    

    <select type="text" name="employment" class="select_by_employment" size="6" v-model="employment">        
        <option> Полная занятость</option>
        <option> Частичная занятость</option>
        <option> Проектная работа</option>
        <option> Стажировка</option>
   </select>
    </div>
    </div>   
    <br>
  
     <transition name="bounce">
    <button class="delete" v-if="this.city!=null || this.employment!=null"><i class="material-icons" @click="delete_select">delete</i></button>
    </transition>
    <transition name="bounce">
    <button v-if="this.city!=null || this.employment!=null" @click="query_filt">Отправить</button>
     </transition>
    </div>
    
</template>



<script>
import Vue from 'vue'
import axios from 'axios' 
    import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)
export default {
    name: 'filtration',
    data(){
        return{
            city: null,
            employment: null,
            id: null,
            
        }
    },
    methods: {
        query_filt(e){
            axios.post('https:/jsonplaceholder.typicode.com/comments',{
                city: this.city,
                employment: this.employment,
            }).then(function(response){
                 alert(JSON.stringify(response.data, null, '\t'))
            })
            .catch(function(error){
                alert(error)
            })
         e.preventDefault();  
        },
        delete_select(){
            this.city = null
            this.employment = null
        }
        
    }
}



</script>




<style scoped>

    .filt {
        background-color: #fffbf8;
        height: 500px;
        overflow: hidden;
        margin-left: 35px;
        margin-top: 35px;
        border-radius: 6px;
        width: 200px;
        vertical-align: middle;
        text-align: center;
        box-shadow: 1px 2px 4px 2px rgba(0,0,0,0.15);
        background-color: #FFFFFF;
   
       
    }
    
    .filt_title h5{
        padding: 7px;
        margin: 0px;
        font-size: 20px;
        font-weight: 300;
        color: white;
        background-color: #039BE5;
    }
    .filt_title {
        
        padding: 0px;
        margin: 0px;
        border-bottom: 2px solid #017cb7 ;
        background-color: #039BE5;
    }    
    select {
    display: inline-block;
       
        

 
        
    }
    .select_by_city  {
        height: 145px;
        
    }     
    select option:hover{ 
        
        color: white;
        border-color: #FF771E;
        border-bottom-color: #039BE5;
        cursor: pointer;
    }    
    select option:active{ 
        background-color: #E55D03;
        color: white;
        border-color: #FF771E;
    }
    
    
    select option:hover, 
select option:focus, 
select option:active, 
select option:checked
{
    color: white !important;
    background: linear-gradient(#039BE5,#039BE5);
    background-color: #039BE5 !important;
}
    
    .select_by_employment{
        height: 120px;
        
    }

    
    p i {
        color: #E55D03;
        vertical-align: middle;
            margin-right: 5px;
        font-size: 25px;
        line-height: 10px;
       
        
    }
    
    
    p:hover {
        box-shadow:  0px 0px 3px 1px #E55D03;
    }
    
    option{
        padding-left: 5px;
        
    }    
    option{
    
    padding: 2px 10px;
        
    }    
    
    option:hover{
    
    
        
    }
    
    
    .filt_by_employment p {
        font-size: 17px;
        color: black;


    }
    .filt_by_city {
        padding: 0px;
        margin: 0px;
    }

     .bloc { 
         display:inline-block;
         overflow:hidden; 

    }

     .bloc select { 
         margin-right: 20px;
         padding-right: 50px; 
         margin:-5px 40px -5px -3px; 
         width: 300px;
         font-size: 14px;
         color: #039BE5;
         
        
         
         
    
    }
    option{
        background-color: white;
        margin-bottom: 2px;
        width: 90%;
        color: black;
        font-weight: 300;
        border-left: 4px solid #039BE5;
        border-bottom: 1.5px solid #E5E5E5;
        margin-left: 4px;
        
    }

    
    button {
        background-color: #039BE5;
        border: none;
        font-size: 18px;
        font-weight: 300;
        border-radius: 10px;
        padding: 6px 15px;
        color: white;
        box-shadow: inset 0px 0px 1px 1px rgba(0,0,0,0.1);
        vertical-align: bottom;
        float: left;
        margin-left: 15px;
    }
    
    .delete{
    padding: 0px;
        
        background-color: #ff771e;
        float: left;
        margin-left: 5px;
        
        }
        
    .delete>i{
    padding: 3px 3px 5px 3px;
        vertical-align: bottom;
        
        
        }
    
        @keyframes bounce {
      0% {
        transform: scale(0);
      }
      80% {
        transform: scale(1.1);
      }
      100% {
        transform: scale(1);
      }
    }
    .bounce-enter-active{
      animation: bounce .5s;
    }
    .bounce-leave-active {
      animation: bounce .5s reverse;
    }
    
    
    internal-list-box {
    
    }



    option:first-child{
        padding-top: 2px;
    }

         p {
            text-align: center;

          font-size: 19px;
        border-radius: 5px;
         padding: 0px;
         margin: 10px;
         font-weight: 400;
        color: black;
        letter-spacing: -1.3px;
        line-height: 30px;
            position: relative;
            padding: 0px 7px;
            padding-bottom: 2px;
            box-shadow:  inset 0px 0px 1px 1px rgba(0,0,0,0.2);

         opacity: 1;
         text-align: center;

         
    }
</style>