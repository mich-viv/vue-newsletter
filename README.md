# vue-newsletter


<b>21/02 Update<:/b> added a new mail file updated with PHPmailer instead of Swiftmailer.<br>

<b>WARNING</b> starting from november 2021 Swiftmailer will no longer be maintained. Currently working on changing the mail handling part with <a href="https://github.com/PHPMailer/PHPMailer">PHPMailer</a>.<br>

This is a simple vue3 project with some php as backend, the focus is to mimic an admin panel and being able to handle the main to a list of user saved in a database.<br>

The admin is able to write a markup version of the message, this is possible thanks to <a href="https://github.com/markedjs/marked"> marked js</a>.
The update of the text is visible in realtime thanks to vuejs.<br>
After the mail text is written, the admin is able to choose an attachment to the mail or, if not present in the server, to upload some file.<br> All those part are done in the backend with php, the request between the Attachment and the Upload components and the backend are done with <a href="https://axios-http.com/docs/intro"> Axios</a>.</br>

After that you can simply sent the mail, this part is handle by the php backend thanks to <a href="https://swiftmailer.symfony.com/docs/introduction.html"> Swiftmailer</a>.  
