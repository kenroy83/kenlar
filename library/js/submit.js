// JavaScript Document
var fullname = new LiveValidation( 'fullname', {onlyOnSubmit: true } );
fullname.add( Validate.Presence );
var email = new LiveValidation( 'email', {onlyOnSubmit: true } );
email.add( Validate.Email );
email.add( Validate.Presence );
var message = new LiveValidation( 'message', {onlyOnSubmit: true } );
message.add( Validate.Presence );

/*var automaticOnSubmit = field1.form.onsubmit;
field1.form.onsubmit = function(){
var valid = automaticOnSubmit();
if(valid)alert('The form is valid!');
return false;
}*/