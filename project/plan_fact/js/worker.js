importScripts("/project/plan_fact/js/workerFakeDOM.js");
importScripts('/project/plan_fact/js/jquery-2.1.4.min.js');
console.log("JQuery version:", $.fn.jquery);

onmessage = function(e) 
{
  var sort_what = e.data[0] ;

  console.log('Message received from main script');
  console.log('Posting message back to main script');

  Sort( sort_what );
  postMessage( ' ' + sort_what );
}
