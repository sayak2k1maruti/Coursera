/*Name this external file gallery.js*/

function upDate(previewPic){
   document.getElementById('image').innerHTML = previewPic.alt;
   document.getElementById('image').style.backgroundImage = "url(" + previewPic.src + ")";
       }
   
       function unDo(){
      document.getElementById('image').style.background = "#8e68ff"; 
      document.getElementById('image').style.background = "#8e68ff"; 
      document.getElementById('image').innerHTML = "Hover over an image below to display here.";
       }
