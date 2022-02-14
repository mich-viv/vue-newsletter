<template>
  <div id="editor">
    <h1 id="title">Newsletter Editor v.1.0</h1>
    
    <form @submit="onSubmit">
      <textarea id="object" v-model="form.object" type="text" required placeholder="write the object of the message"></textarea>
      <span id="description">Below here will appear the preview of the email. Write it on the left with <a href="https://www.w3schools.com/TAGS/default.ASP">html tags</a></span>
      <br>
      <textarea :value="form.input" @input="update"></textarea>
      <div v-html="compiledMarkdown" :value="form.markdown"></div>
      <button type="submit">
        Send email
      </button>
      <input type="hidden" id="attachment" name="attachment" :value="form.attachment"/>
    </form>
    <p>Upload a file to the server and then select it as an attachment to the mail</p>
      <Upload style="height:40px;"></Upload>
      <p>Select an attachment to the email: {{ attachment }}</p>
      <Attachment style="height:40px;"  v-on:selected="getAttachment"></Attachment>
  </div>
</template>


<script>
import { marked } from 'marked'
import axios from 'axios'
import Upload from './components/Upload'
import Attachment from './components/Attachment'

const querystring = require("querystring");

export default {
    data() {
        return {
          
          form: {
              attachment:'',
              object: '',
              input: '<h1>hello</h1> \n<p>test</p> \n<b style="color:red;">redbold</b>',
          }
        };
    },
    components: {
      Upload,
      Attachment
    },
    computed: {
      compiledMarkdown: function () {
        return marked(this.form.input)
      }
    },
    methods: {
      update: function (e) {
        this.form.input = e.target.value;
      },
      getAttachment(attachment) {
        this.form.attachment = attachment;
      },
      onSubmit(e) {
          e.preventDefault();
          axios.post("backend/mail.php",querystring.stringify(this.form)).then(res => {
            console.log(res);
          });
      }
    }
};
</script>

<style scoped>
html, body {
  margin: 0;
  height: 100%;
  font-family: 'Helvetica Neue', Arial, sans-serif;
  color: #333;
}

h1{
  font-family: Avenir, Helvetica, Arial, sans-serif;
  text-align: center;
}

#object {
font-family: 'Monaco', courier, monospace;
font-size: 12px;
width: 49%;
height: 10vh;
}

#description {
font-family: 'Monaco', courier, monospace;
font-size: 15px;
width: 49%;
height: 10vh;
padding: 20px;
}



textarea, #editor div {
  display: inline-block;
  width: 49%;
  height: 55vh;
  vertical-align: top;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  padding: 0 20px;
}

textarea {
  border: none;
  border-right: 1px solid #ccc;
  resize: none;
  outline: none;
  background-color: #f6f6f6;
  font-size: 14px;
  font-family: 'Monaco', courier, monospace;
  padding: 20px;
}

code {
  color: #f66;
}
</style>
