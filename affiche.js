var win = null;
function NewWindow(mypage,myname,w,h,scroll,pos,niveau)
{
 
 if(pos=="random"){LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}
 if(pos=="center"){LeftPosition=(screen.width)?(screen.width-w)/2:100;TopPosition=(screen.height)?(screen.height-h)/2:100;}
 else if((pos!="center" && pos!="random") || pos==null){LeftPosition=0;TopPosition=20}
 settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';
 
 if((win == null || win.closed)&&(mypage!='#'))
 { win=window.open(mypage,myname,settings); win.focus(); } else
 {win.close(); if ((mypage!='#')) { win=window.open(mypage,myname,settings);win.focus();}}
 
}