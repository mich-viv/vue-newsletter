<template>
    <div id="upload">
        <label>File
        <input type="file" id="file" ref="file" v-on:change="onChangeFileUpload()"/>
        </label>
        <button v-on:click="upload(), isHidden=true">Upload</button>
        <p v-if="isHidden">Upload successful</p>
    </div>
</template>

  

<script>

import axios from 'axios'

export default {
    data(){
      return {
        isHidden: false,
        file: ''
      }
    },
    methods: {
        upload(){
            
            let formData = new FormData();
            
            formData.append('file', this.file);
            axios.post('backend/upload.php', formData, {
                headers: {'Content-Type': 'multipart/form-data'}
            }).then(function(data){
            
            console.log(data.data);
            
            }).catch(function(){
            
            console.log('FAILURE!!');
        });

        
    },
    onChangeFileUpload(){
        this.file = this.$refs.file.files[0];
        }
    }
}
</script>

<style scoped>
#upload{
    height:70px;
}
</style>
