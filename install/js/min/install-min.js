/*
Title:			Install Wizard Javascript
Author:			Sam Rayner - http://samrayner.com
Created:		2011-12-28
*/var CleanUp={done:function(){$("#cleanup-button").removeClass("active").addClass("done").attr("data-icon","2").html("Files deleted");$("#cleanup, #last-panel").toggleClass("disabled");CleanUp.disable()},click:function(a){a.preventDefault();$(this).removeClass("done").addClass("active").attr("data-icon","0").html("Deleting install files");var b=$.get("cleanup.php",CleanUp.done)},init:function(){$("#cleanup-button").click(CleanUp.click)},disable:function(){$("#cleanup-button").off("click")}},Install={done:function(){$("#install-button").removeClass("active").removeAttr("style").addClass("done").attr("data-icon","2").html("Content added to Dropbox");Install.nextStep()},nextStep:function(a){a&&a.preventDefault();$("#install, #cache").toggleClass("disabled");Install.disable();Recache.init()},updateProgress:function(){var a=$.ajax({url:"install_log.txt",complete:function(a){var b=a.responseText;if(!b)return window.setTimeout(Install.updateProgress,500);var c=b.split("\n"),d=c[2].replace(/\D/g,"");c.splice(0,4);var e=c.length;if(e>d)return!0;if(e>0){$("#install-button").html(c[e-1].replace(/^\t+/,""));var f=$("#install-button").outerWidth(),g=e/d*100;$("#install-button").css("background-position-x",Math.round(f*g/100)+"px")}window.setTimeout(Install.updateProgress,500)}})},run:function(a){var b=$.ajax({url:"install_content.php",data:{host_root:$("host_root").val()},complete:Install.done});window.setTimeout(Install.updateProgress,1e3)},click:function(a){a.preventDefault();$(this).removeClass("done").addClass("active").attr("data-icon","0").html("Preparing files...");Install.run()},init:function(){$("#install-button").click(Install.click);$("#install .skip").click(Install.nextStep)},disable:function(){$("#install-button, #install .skip").off("click")}};$(function(){Install.init()});