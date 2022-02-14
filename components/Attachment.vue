<template>
    <div>       
        <select v-model="selected" v-on:change="onChange($event)">
            <option value='0' disabled>Please select one</option>
            <!--<option :value='fileNames'>{{ fileNames }}</option>-->
            <option v-for='item in files' :key='item' :value='item'>{{ item }}</option>
        </select>
        <br>
        <span>Selected attachment: {{selected}}</span>
    </div>
</template>

  
<script>

import axios from 'axios'


export default {
    data() {
        return{
            attachment: '',
            selected: '',
            files: []
                
        }
    },
    methods: {

        onChange(e){
                this.selected=e.target.value;
                this.$emit("selected", e.target.value);
                this.attachment=e.target.value;
        },

        getFile: function(){
            
            axios.post('backend/attachment.php').then(res =>{

                let txt=res.data.split("~~~");

                for(let i=0; i<txt.length;i++){
                    if(txt[i]!=""){
                        this.files.push(txt[i]);
                    }
                }
            }); 
        }
    },
    created: function(){
        this.getFile();      
    }
}
</script>

<style scoped>

</style>
