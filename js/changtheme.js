// JavaScript Document

var webInfo = {
	webLang : "TH",
	webTheme : 2
};

if (window.localStorage.getItem("webInfo")!=undefined && window.localStorage.getItem("webInfo")!=null) {
	webInfo = JSON.parse(window.localStorage.getItem("webInfo"));
} else {
	window.localStorage.setItem("webInfo",JSON.stringify(webInfo));
}

const monthNames = {
	"EN":["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
]};

// กำหนดค่าธีมให้เป็นปกติทุกครั้งเมื่อเข้าเวป
webInfo.webTheme = 2;

// เซทค่าวันที่ปัจจุบันในปฏิทิน
var myToday = new Date();
$("#showDate").text(myToday.getDate());
$("#showMonthYear").text(monthNames.EN[myToday.getMonth()]+" "+myToday.getFullYear());

// เซทค่าภาษาตามที่อ่านได้จาก storage
if(webInfo.webLang=="TH") {
	$("#change_lang_T").addClass("lang_active");
	$("#change_lang_E").removeClass("lang_active");	
}
if(webInfo.webLang=="EN") {
	$("#change_lang_T").removeClass("lang_active");
	$("#change_lang_E").addClass("lang_active");	
}

// ============= ทำการขึ้นปุ่ม Scroll Up =============
window.onscroll = function() {scrollFunction()};

function setActiveStyleSheet(title) {

   var i, a, main;

   for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {

     if (a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
       a.disabled = true;
		 
       if (a.getAttribute("title") == title) a.disabled = false;
     }
   }
}	



function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}


	
	$("#smallerSizeFont").click(function(){
		$("html").css("font-size","80%");
	});
	$("#normalSizeFont").click(function(){
		$("html").css("font-size","100%");
	});
	$("#biggerSizeFont").click(function(){
		$("html").css("font-size","125%");
	});
	
	
	// ======================= เปลี่ยนธีม -========================= //
    
    function changeThemeToNormal() {
		//กลับเป็นปกติ
		
		$("html").css("filter","");
		$("html").css("-o-filter","");
		$("html").css("-moz-filter","");
		$("html").css("-webkit-filter","");		
		
		setActiveStyleSheet("default");
        setAllImageToNormal();  
    }
    
    function changeThemeToBlindWhite () {

		// ภาพและตัวหนังสือเป็นสีขาว พื้นดำ
		setActiveStyleSheet("default");		
		setAllImageToNormal();
		
		$("html").css("filter","grayscale(100%) contrast(130%) ");
		$("html").css("-o-filter","grayscale(100%) contrast(130%)");
		$("html").css("-moz-filter","grayscale(100%) contrast(130%)");
		$("html").css("-webkit-filter","grayscale(100%) contrast(130%)");		
			        
    }
    
    function changeThemeToBlindYellow() {

		$("html").css("filter","");
		$("html").css("-o-filter","");
		$("html").css("-moz-filter","");
		$("html").css("-webkit-filter","");     
		//ตัวหนังสือเหลือง บนพื้นขาวดำ

		setActiveStyleSheet("blind_yellow");
        setAllImageToYellow();
            
    }
    

	
    function setAllImageToYellow() {
        
		var newFile;

		if(document.getElementsByClassName("icon1669").length > 0) {
			newFile = getPathName($(".icon1669")[0].src) + "/" + "yellow_" + getFileName($(".icon1669")[0].src);
			$(".icon1669").prop("src",newFile);			
		}
		if(document.getElementsByClassName("loginIcon").length > 0) {
			newFile = getPathName($(".loginIcon img")[0].src) + "/" + "yellow_" + getFileName($(".loginIcon img")[0].src);		
			$(".loginIcon img").prop("src",newFile);   		
		}
		if(document.getElementsByClassName("searchIcon").length > 0) {
			newFile = getPathName($(".searchIcon img")[0].src) + "/" + "yellow_" + getFileName($(".searchIcon img")[0].src);	
			$(".searchIcon img").prop("src",newFile);		
		}	
		if($("img[title='Facebook']").length>0) {
			newFile = getPathName($("img[title='Facebook']")[0].src) + "/" + "yellow_" + getFileName($("img[title='Facebook']")[0].src);	
			$("img[title='Facebook']").prop("src",newFile);		   
		}
		newFile = getPathName($("img[title='Youtube']")[0].src) + "/" + "yellow_" + getFileName($("img[title='Youtube']")[0].src);
		$("img[title='Youtube']").prop("src",newFile);
		newFile = getPathName($("img[title='Tweeter']")[0].src) + "/" + "yellow_" + getFileName($("img[title='Tweeter']")[0].src);
		$("img[title='Tweeter']").prop("src",newFile);
		newFile = getPathName($("img[title='Site Map']")[0].src) + "/" + "yellow_" + getFileName($("img[title='Site Map']")[0].src);
		$("img[title='Site Map']").prop("src",newFile);
		newFile = getPathName($("img[title='email']")[0].src) + "/" + "yellow_" + getFileName($("img[title='email']")[0].src);
		$("img[title='email']").prop("src",newFile);			
        
        $(".border-blue").addClass("border-yellow").removeClass("border-blue");
        $(".color-blue").addClass("color-yellow").removeClass("color-blue");
		
		$(".bg-blue").addClass("bg-black").removeClass("bg-blue");
		$(".color-white").addClass("color-yellow").removeClass("color-white");
        
        $("#icon_section3 img").css("filter","brightness(0) saturate(100%)invert(86%) sepia(62%) saturate(7365%) hue-rotate(355deg) brightness(105%) contrast(107%)");
        $("#icon_section3 img").css("-o-filter","brightness(0) saturate(100%)invert(86%) sepia(62%) saturate(7365%) hue-rotate(355deg) brightness(105%) contrast(107%)");
        $("#icon_section3 img").css("-moz-filter","brightness(0) saturate(100%)invert(86%) sepia(62%) saturate(7365%) hue-rotate(355deg) brightness(105%) contrast(107%)");
        $("#icon_section3 img").css("-webkit-filter","brightness(0) saturate(100%)invert(86%) sepia(62%) saturate(7365%) hue-rotate(355deg) brightness(105%) contrast(107%)");
               
    }
    
    function setAllImageToNormal() {
        
		var newFile;
		
		if(document.getElementsByClassName("icon1669").length > 0) {
			newFile = getPathName($(".icon1669")[0].src) + "/" + getNormalFileName($(".icon1669")[0].src);	
			$(".icon1669").prop("src",newFile);			
		}
		if(document.getElementsByClassName("loginIcon").length > 0) {
			newFile = getPathName($(".loginIcon img")[0].src) + "/" + getNormalFileName($(".loginIcon img")[0].src);	
			$(".loginIcon img").prop("src",newFile);	
		}
  		if(document.getElementsByClassName("searchIcon").length > 0) {
			newFile = getPathName($(".searchIcon img")[0].src) + "/" + getNormalFileName($(".searchIcon img")[0].src);			
	        $(".searchIcon img").prop("src",newFile);  
		}
		if($("img[title='Facebook']").length>0) {
			newFile = getPathName($("img[title='Facebook']")[0].src) + "/" +getNormalFileName($("img[title='Facebook']")[0].src);	
			$("img[title='Facebook']").prop("src",newFile);		   
		}

		newFile = getPathName($("img[title='Youtube']")[0].src) + "/" +getNormalFileName($("img[title='Youtube']")[0].src);	
		$("img[title='Youtube']").prop("src",newFile);
		newFile = getPathName($("img[title='Tweeter']")[0].src) + "/" + getNormalFileName($("img[title='Tweeter']")[0].src);
		$("img[title='Tweeter']").prop("src",newFile);
		newFile = getPathName($("img[title='Site Map']")[0].src) + "/" + getNormalFileName($("img[title='Site Map']")[0].src);
		$("img[title='Site Map']").prop("src",newFile);	
		
		newFile = getPathName($("img[title='email']")[0].src) + "/" + getNormalFileName($("img[title='email']")[0].src);
		$("img[title='email']").prop("src",newFile);				
        
        $(".border-yellow").addClass("border-blue").removeClass("border-yellow");
        $(".color-yellow").addClass("color-blue").removeClass("color-yellow");

		$(".bg-black").addClass("bg-blue").removeClass("bg-black");
		$(".color-yellow").addClass("color-white").removeClass("color-yellow");		
        
        $("#icon_section3 img").css("filter","");
        $("#icon_section3 img").css("-o-filter","");
        $("#icon_section3 img").css("-moz-filter","");
        $("#icon_section3 img").css("-webkit-filter","");   
            
    }
	function goTop() {
		alert("goTop");
	}

	function goPrevious() {
		alert("goPrevious");	
	}

	function goNext() {
		alert("goNext");	
	}

	function goLast() {
		alert("goLast");	
	}	
    // ====================== Event การกดปุ่มต่่างๆ ====================== //
	$("#theme1").click(function(){
		if(webInfo.webTheme!=1) {
			webInfo.webTheme = 1;
			window.localStorage.setItem("webInfo",JSON.stringify(webInfo));	
			changeThemeToBlindWhite();			
		}

	});
	
	$("#theme2").click(function(){
		if(webInfo.webTheme!=2){
			webInfo.webTheme = 2;
			window.localStorage.setItem("webInfo",JSON.stringify(webInfo));	
			changeThemeToNormal();			
		}

	});
	
	$("#theme3").click(function(){
		if(webInfo.webTheme!=3){
			webInfo.webTheme = 3;
			window.localStorage.setItem("webInfo",JSON.stringify(webInfo));	
			changeThemeToBlindYellow();			
		}
	});	
    
    // =========== set theme =========== //
