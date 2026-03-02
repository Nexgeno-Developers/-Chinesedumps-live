/***********************************************************
InnovaStudio WYSIWYG Editor 3.0
© 2007, InnovaStudio (www.innovastudio.com). All rights reserved.
************************************************************/

var UA = navigator.userAgent.toLowerCase();
var isIE = (UA.indexOf('msie') >= 0) ? true : false;
var isNS = (UA.indexOf('mozilla') >= 0) ? true : false;

/*** UTILITY OBJECT ***/
var oUtil=new InnovaEditorUtil();
function InnovaEditorUtil()
  {
  /*** Localization ***/
  this.langDir="english";
  try{if(LanguageDirectory)this.langDir=LanguageDirectory;}catch(e){;}
  var oScripts=document.getElementsByTagName("script");
  for(var i=0;i<oScripts.length;i++)
    {
    var sSrc=oScripts[i].src.toLowerCase();
    if(sSrc.indexOf("scripts/editor.js")!=-1) this.scriptPath=oScripts[i].src.replace(/editor.js/ig,"");
    }
  this.scriptPathLang=this.scriptPath+"language/"+this.langDir+"/";
  if(this.langDir=="english")
    document.write("<scr"+"ipt src='"+this.scriptPathLang+"editor_lang.js'></scr"+"ipt>");
  /*** /Localization ***/

  this.oName;this.oEditor;this.obj;
  this.oSel;
  this.sType;
  this.bInside=bInside;
  this.useSelection=true;
  this.arrEditor=[];
  this.onSelectionChanged=function(){return true;};
  this.activeElement;
  
  this.spcCharCode=[[169, "&copy;"],[163, "&pound;"],[174, "&reg;"],[233, "&eacute;"],[201, "&Eacute;"],[8364,"&euro;"]];
  this.spcChar=[];
  for(var i=0;i<this.spcCharCode.length;i++)
    {
    this.spcChar[i]=[new RegExp(String.fromCharCode(this.spcCharCode[i][0]), "g"), this.spcCharCode[i][1]];
    }
  
  this.replaceSpecialChar=function(sHTML) 
    {
    for(var i=0;i<this.spcChar.length;i++)
      {
      sHTML=sHTML.replace(this.spcChar[i][0], this.spcChar[i][1]);
      }
    return sHTML;
    }
    
  }

/*** FOCUS STUFF ***/
function bInside(oElement)
  {
  while(oElement!=null)
    {
    if(oElement.contentEditable=="true")return true;
    oElement=oElement.parentElement;
    }
  return false;
  }
function checkFocus()
  {
  var oEditor=eval("idContent"+this.oName);
  var oSel=oEditor.document.selection.createRange();
  var sType=oEditor.document.selection.type;

  if(oSel.parentElement!=null)
    {
    if(!bInside(oSel.parentElement()))return false;
    }
  else
    {
    if(!bInside(oSel.item(0)))return false;
    }
  return true;
  }
function iwe_focus()
  {
  var oEditor=eval("idContent"+this.oName);
  oEditor.focus()
  }

/*** EDITOR OBJECT ***/
function InnovaEditor(oName)
  {
  this.oName=oName;
  this.RENDER=RENDER;
  this.init=initISEditor;
  this.IsSecurityRestricted=false;

  this.loadHTML=loadHTML;
  this.putHTML=putHTML;
  this.getHTMLBody=getHTMLBody;
  this.getXHTMLBody=getXHTMLBody;
  this.getHTML=getHTML;
  this.getXHTML=getXHTML;
  this.getTextBody=getTextBody;
  this.initialRefresh=false;
  this.preserveSpace=false;

  this.bInside=bInside;
  this.checkFocus=checkFocus;
  this.focus=iwe_focus;
  
  this.onKeyPress=function(){return true;};
  
  this.styleSelectionHoverBg="#acb6bf";
  this.styleSelectionHoverFg="white";

  //clean
  this.cleanEmptySpan=cleanEmptySpan;
  this.cleanFonts=cleanFonts;
  this.cleanTags=cleanTags;
  this.replaceTags=replaceTags;
  this.cleanDeprecated=cleanDeprecated;

  this.doClean=doClean;
  this.applySpanStyle=applySpanStyle;
  this.applyLine=applyLine;
  this.applyBold=applyBold;
  this.applyItalic=applyItalic;

  this.doOnPaste=doOnPaste;
  this.isAfterPaste=false;

  this.doCmd=doCmd;
  this.applyParagraph=applyParagraph;
  this.applyFontName=applyFontName;
  this.applyFontSize=applyFontSize;
  this.applyBullets=applyBullets;
  this.applyNumbering=applyNumbering;
  this.applyJustifyLeft=applyJustifyLeft;
  this.applyJustifyCenter=applyJustifyCenter;
  this.applyJustifyRight=applyJustifyRight;
  this.applyJustifyFull=applyJustifyFull;
  this.applyBlockDirLTR=applyBlockDirLTR;
  this.applyBlockDirRTL=applyBlockDirRTL;
  this.doPaste=doPaste;
  this.doPasteText=doPasteText;
  this.applySpan=applySpan;
  this.makeAbsolute=makeAbsolute;
  this.insertHTML=insertHTML;
  this.clearAll=clearAll;
  this.insertCustomTag=insertCustomTag;
  this.selectParagraph=selectParagraph;
  
  this.hide=hide;

  this.width="620";
  this.height="350";
  this.publishingPath="";//ex."http://localhost/innovastudio/"

  var oScripts=document.getElementsByTagName("script");
  for(var i=0;i<oScripts.length;i++)
    {
    var sSrc=oScripts[i].src.toLowerCase();
    if(sSrc.indexOf("scripts/editor.js")!=-1) this.scriptPath=oScripts[i].src.replace(/editor.js/,"");
    }

  this.iconPath="icons/";
  this.iconWidth=23; //25;
  this.iconHeight=25; //22;
  this.iconOffsetTop;//not used

  this.dropTopAdjustment=-1;
  this.dropLeftAdjustment=0;

  this.runtimeBorder=runtimeBorder;
  this.runtimeBorderOn=runtimeBorderOn;
  this.runtimeBorderOff=runtimeBorderOff;
  this.IsRuntimeBorderOn=true;
  this.runtimeStyles=runtimeStyles;

  this.applyColor=applyColor;
  this.customColors=[];//["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9"];
  this.oColor1 = new ColorPicker("oColor1",this.oName);//to call: oEdit1.oColor1
  this.oColor2 = new ColorPicker("oColor2",this.oName);//rendered id: ...oColor1oEdit1
  this.expandSelection=expandSelection;

  this.fullScreen=fullScreen;
  this.stateFullScreen=false;
  this.onFullScreen=function(){return true;};
  this.onNormalScreen=function(){return true;};

  this.arrElm=new Array(300);
  this.getElm=iwe_getElm;

  this.features=[];
  /*
  this.buttonMap=["Save","FullScreen","Preview","Print","Search","SpellCheck","|",
      "Cut","Copy","Paste","PasteWord","PasteText","|","Undo","Redo","|",
      "ForeColor","BackColor","|","Bookmark","Hyperlink",
      "Image","Flash","Media","ContentBlock","InternalLink","InternalImage","CustomObject","|",
      "Table","Guidelines","Absolute","|","Characters","Line",
      "Form","RemoveFormat","HTMLFullSource","HTMLSource","XHTMLFullSource",
      "XHTMLSource","ClearAll","BRK",
      "StyleAndFormatting","Styles","|","CustomTag","Paragraph","FontName","FontSize","|",
      "Bold","Italic","Underline","Strikethrough","Superscript","Subscript","|",
      "JustifyLeft","JustifyCenter","JustifyRight","JustifyFull","|",
      "Numbering","Bullets","|","Indent","Outdent","LTR","RTL"];//complete, default
  */
  this.buttonMap=["Save","FullScreen","Preview","Print","Search","SpellCheck",
      "Cut","Copy","Paste","PasteWord","PasteText","Undo","Redo",
      "ForeColor","BackColor","Bookmark","Hyperlink",
      "Image","Flash","Media","ContentBlock","InternalLink","InternalImage","CustomObject",
      "Table","Guidelines","Absolute","Characters","Line",
      "Form","RemoveFormat","HTMLFullSource","HTMLSource","XHTMLFullSource",
      "XHTMLSource","ClearAll","BRK",
      "StyleAndFormatting","Styles","CustomTag","Paragraph","FontName","FontSize",
      "Bold","Italic","Underline","Strikethrough","Superscript","Subscript",
      "JustifyLeft","JustifyCenter","JustifyRight","JustifyFull",
      "Numbering","Bullets","Indent","Outdent","LTR","RTL"];//complete, default

  this.btnSave=false;this.btnPreview=true;this.btnFullScreen=true;this.btnPrint=false;this.btnSearch=true;
  this.btnSpellCheck=false;this.btnTextFormatting=true;
  this.btnListFormatting=true;this.btnBoxFormatting=true;this.btnParagraphFormatting=true;this.btnCssText=true;this.btnCssBuilder=false;
  this.btnStyles=false;this.btnParagraph=true;this.btnFontName=true;this.btnFontSize=true;
  this.btnCut=true;this.btnCopy=true;this.btnPaste=true;this.btnPasteText=false;this.btnUndo=true;this.btnRedo=true;
  this.btnBold=true;this.btnItalic=true;this.btnUnderline=true;
  this.btnStrikethrough=false;this.btnSuperscript=false;this.btnSubscript=false;
  this.btnJustifyLeft=true;this.btnJustifyCenter=true;this.btnJustifyRight=true;this.btnJustifyFull=true;
  this.btnNumbering=true;this.btnBullets=true;this.btnIndent=true;this.btnOutdent=true;
  this.btnLTR=false;this.btnRTL=false;this.btnForeColor=true;this.btnBackColor=true;
  this.btnHyperlink=true;this.btnBookmark=true;this.btnCharacters=true;this.btnCustomTag=false;
  this.btnImage=true;this.btnFlash=false;this.btnMedia=false;
  this.btnTable=true;this.btnGuidelines=true;
  this.btnAbsolute=true;this.btnPasteWord=true;this.btnLine=true;
  this.btnForm=true;this.btnRemoveFormat=true;
  this.btnHTMLFullSource=false;this.btnHTMLSource=false;
  this.btnXHTMLFullSource=false;this.btnXHTMLSource=true;
  this.btnClearAll=false;

  this.tabs=null;
  this.groups=null;

  //*** CMS Features ***
  this.cmdAssetManager="";
  
  this.cmdFileManager="";
  this.cmdImageManager="";
  this.cmdMediaManager="";
  this.cmdFlashManager="";
  
  this.btnContentBlock=false;
  this.cmdContentBlock=";";//needs ;
  this.btnInternalLink=false;
  this.cmdInternalLink=";";//needs ;
  this.insertLink=insertLink;
  this.btnCustomObject=false;
  this.cmdCustomObject=";";//needs ;
  this.btnInternalImage=false;
  this.cmdInternalImage=";";//needs ; 
  //*****

  this.css="";
  this.arrStyle=[];
  this.isCssLoaded=false;
  this.openStyleSelect=openStyleSelect;

  this.arrParagraph=[[getTxt("Heading 1"),"H1"],
            [getTxt("Heading 2"),"H2"],
            [getTxt("Heading 3"),"H3"],
            [getTxt("Heading 4"),"H4"],
            [getTxt("Heading 5"),"H5"],
            [getTxt("Heading 6"),"H6"],
            [getTxt("Preformatted"),"PRE"],
            [getTxt("Normal (P)"),"P"],
            [getTxt("Normal (DIV)"),"DIV"]];

  this.arrFontName=["Arial","Arial Black","Arial Narrow",
            "Book Antiqua","Bookman Old Style",
            "Century Gothic","Comic Sans MS","Courier New",
            "Franklin Gothic Medium","Garamond","Georgia",
            "Impact","Lucida Console","Lucida Sans","Lucida Unicode",
            "Modern","Monotype Corsiva","Palatino Linotype",
            "Roman","Script","Small Fonts","Symbol",
            "Tahoma","Times New Roman","Trebuchet MS",
            "Verdana","Webdings","Wingdings","Wingdings 2","Wingdings 3",
            "serif","sans-serif","cursive","fantasy","monoscape"];

  this.arrFontSize=[[getTxt("Size 1"),"1"],
            [getTxt("Size 2"),"2"],
            [getTxt("Size 3"),"3"],
            [getTxt("Size 4"),"4"],
            [getTxt("Size 5"),"5"],
            [getTxt("Size 6"),"6"],
            [getTxt("Size 7"),"7"]];

  this.arrCustomTag=[];//eg.[["Full Name","{%full_name%}"],["Email","{%email%}"]];

  this.docType="";
  this.html="<html>";
  this.headContent="";
  this.preloadHTML="";

  this.onSave=function(){document.getElementById("iwe_btnSubmit"+this.oName).click()};
  this.useBR=false;
  this.useDIV=true;

  this.doUndo=doUndo;
  this.doRedo=doRedo;
  this.saveForUndo=saveForUndo;
  this.arrUndoList=[];
  this.arrRedoList=[];

  this.useTagSelector=true;
  this.TagSelectorPosition="bottom";
  this.moveTagSelector=moveTagSelector;
  this.selectElement=selectElement;
  this.removeTag=removeTag;
  this.doClick_TabCreate=doClick_TabCreate;
  this.doRefresh_TabCreate=doRefresh_TabCreate;

  this.arrCustomButtons = [["CustomName1","alert(0)","caption here","btnSave.gif"],
              ["CustomName2","alert(0)","caption here","btnSave.gif"]];

  this.onSelectionChanged=function(){return true;};
  
  this.spellCheckMode="ieSpell";//NetSpell

  this.REPLACE=REPLACE;
  this.idTextArea;
  this.mode="HTMLBody";
  
  var me=this;
  this.tbar=new ISToolbarManager(this.oName);
}

/*** UNDO/REDO ***/
function saveForUndo()
  {
  var oEditor=eval("idContent"+this.oName);
  var obj=eval(this.oName);
  if(obj.arrUndoList[0])
    if(oEditor.document.body.innerHTML==obj.arrUndoList[0][0])return;
  for(var i=20;i>1;i--)obj.arrUndoList[i-1]=obj.arrUndoList[i-2];
  obj.focus();
  var oSel=oEditor.document.selection.createRange();
  var sType=oEditor.document.selection.type;

  if(sType=="None")
    obj.arrUndoList[0]=[oEditor.document.body.innerHTML,
      oEditor.document.selection.createRange().getBookmark(),"None"];
  else if(sType=="Text")
    obj.arrUndoList[0]=[oEditor.document.body.innerHTML,
      oEditor.document.selection.createRange().getBookmark(),"Text"];
  else if(sType=="Control")
    {
    oSel.item(0).selThis="selThis";
    obj.arrUndoList[0]=[oEditor.document.body.innerHTML,null,"Control"];
    oSel.item(0).removeAttribute("selThis",0);
    }
  this.arrRedoList=[];//clear redo list

  if(this.btnUndo) this.tbar.btns["btnUndo"+this.oName].setState(1);//makeEnableNormal(eval("document.all.btnUndo"+this.oName));
  if(this.btnRedo) this.tbar.btns["btnRedo"+this.oName].setState(5);//makeDisabled(eval("document.all.btnRedo"+this.oName));
  }
function doUndo()
  {
  var oEditor=eval("idContent"+this.oName);
  var obj=eval(this.oName);
  if(!obj.arrUndoList[0])return;
  //~~~~
  for(var i=20;i>1;i--)obj.arrRedoList[i-1]=obj.arrRedoList[i-2];
  var oSel=oEditor.document.selection.createRange();
  var sType=oEditor.document.selection.type;
  if(sType=="None")
    this.arrRedoList[0]=[oEditor.document.body.innerHTML,
      oEditor.document.selection.createRange().getBookmark(),"None"];
  else if(sType=="Text")
    this.arrRedoList[0]=[oEditor.document.body.innerHTML,
      oEditor.document.selection.createRange().getBookmark(),"Text"];
  else if(sType=="Control")
    {
    oSel.item(0).selThis="selThis";
    this.arrRedoList[0]=[oEditor.document.body.innerHTML,null,"Control"];
    oSel.item(0).removeAttribute("selThis",0);
    }
  //~~~~
  sHTML=obj.arrUndoList[0][0];
  sHTML=fixPathEncode(sHTML);
  oEditor.document.body.innerHTML=sHTML;
  fixPathDecode(oEditor);

  //*** RUNTIME STYLES ***
  this.runtimeBorder(false);
  this.runtimeStyles();
  //***********************
  var oRange=oEditor.document.body.createTextRange();
  if(obj.arrUndoList[0][2]=="None")
    {
    oRange.moveToBookmark(obj.arrUndoList[0][1]);
    oRange.select(); //di-disable, spy tdk select all? tdk perlu utk undo
    }
  else if(obj.arrUndoList[0][2]=="Text")
    {
    oRange.moveToBookmark(obj.arrUndoList[0][1]);
    oRange.select();
    }
  else if(obj.arrUndoList[0][2]=="Control")
    {
    for(var i=0;i<oEditor.document.all.length;i++)
      {
      if(oEditor.document.all[i].selThis=="selThis")
        {
        var oSelRange=oEditor.document.body.createControlRange();
        oSelRange.add(oEditor.document.all[i]);
        oSelRange.select();
        oEditor.document.all[i].removeAttribute("selThis",0);
        }
      }
    }
  //~~~~
  for(var i=0;i<19;i++)obj.arrUndoList[i]=obj.arrUndoList[i+1];
  obj.arrUndoList[19]=null;
  realTime(this.oName);
  }
function doRedo()
  {
  var oEditor=eval("idContent"+this.oName);
  var obj=eval(this.oName);
  if(!obj.arrRedoList[0])return;
  //~~~~
  for(var i=20;i>1;i--)obj.arrUndoList[i-1]=obj.arrUndoList[i-2];
  var oSel=oEditor.document.selection.createRange();
  var sType=oEditor.document.selection.type;
  if(sType=="None")
    obj.arrUndoList[0]=[oEditor.document.body.innerHTML,
      oEditor.document.selection.createRange().getBookmark(),"None"];
  else if(sType=="Text")
    obj.arrUndoList[0]=[oEditor.document.body.innerHTML,
      oEditor.document.selection.createRange().getBookmark(),"Text"];
  else if(sType=="Control")
    {
    oSel.item(0).selThis="selThis";
    this.arrUndoList[0]=[oEditor.document.body.innerHTML,null,"Control"];
    oSel.item(0).removeAttribute("selThis",0);
    }
  //~~~~
  sHTML=obj.arrRedoList[0][0];
  sHTML=fixPathEncode(sHTML);
  oEditor.document.body.innerHTML=sHTML;
  fixPathDecode(oEditor);
  
  //*** RUNTIME STYLES ***
  this.runtimeBorder(false);
  this.runtimeStyles();
  //***********************
  var oRange=oEditor.document.body.createTextRange();
  if(obj.arrRedoList[0][2]=="None")
    {
    oRange.moveToBookmark(obj.arrRedoList[0][1]);
    //oRange.select(); //di-disable, sph tdk select all, utk redo perlu
    }
  else if(obj.arrRedoList[0][2]=="Text")
    {
    oRange.moveToBookmark(obj.arrRedoList[0][1]);
    oRange.select();
    }
  else if(obj.arrRedoList[0][2]=="Control")
    {
    for(var i=0;i<oEditor.document.all.length;i++)
      {
      if(oEditor.document.all[i].selThis=="selThis")
        {
        var oSelRange = oEditor.document.body.createControlRange();
        oSelRange.add(oEditor.document.all[i]);
        oSelRange.select();
        oEditor.document.all[i].removeAttribute("selThis",0);
        }
      }
    }
  //~~~~
  for(var i=0;i<19;i++)obj.arrRedoList[i]=obj.arrRedoList[i+1];
  obj.arrRedoList[19]=null;
  realTime(this.oName);
  }

/*** RENDER ***/
var bOnSubmitOriginalSaved=false;
function REPLACE(idTextArea, dvId)
  {
  this.idTextArea=idTextArea;
  var oTextArea=document.getElementById(idTextArea);
  oTextArea.style.display="none";
  var oForm=oTextArea.form;
  if(oForm)
    {
    if(!bOnSubmitOriginalSaved)
      {
      onsubmit_original=oForm.onsubmit;
      
      bOnSubmitOriginalSaved=true;  
      }
    oForm.onsubmit = new Function("return onsubmit_new()");
    }
  
  var sContent=document.getElementById(idTextArea).value;
  sContent=sContent.replace(/&/g,"&amp;");
  sContent=sContent.replace(/</g,"&lt;");
  sContent=sContent.replace(/>/g,"&gt;");
  
  this.RENDER(sContent, dvId);
  }
function onsubmit_new()
  {
  var sContent;
  for(var i=0;i<oUtil.arrEditor.length;i++)
    {
    var oEdit=eval(oUtil.arrEditor[i]);
    
    var oEditor=eval("idContent"+oEdit.oName);
    var allSpans = oEditor.document.getElementsByTagName("SPAN");
    for (var j=0; j<allSpans.length; j++)
      {
      if ((allSpans[j].innerHTML=="") && (allSpans[j].parentElement.children.length==1))
        {
        allSpans[j].innerHTML = "&nbsp;";
        }
      }

    if(oEdit.mode=="HTMLBody")sContent=oEdit.getHTMLBody();
    if(oEdit.mode=="HTML")sContent=oEdit.getHTML();
    if(oEdit.mode=="XHTMLBody")sContent=oEdit.getXHTMLBody();
    if(oEdit.mode=="XHTML")sContent=oEdit.getXHTML();
    document.getElementById(oEdit.idTextArea).value=sContent;
    }
  if(onsubmit_original)return onsubmit_original();
  }
function onsubmit_original(){}

var iconHeight;//icons related
function RENDER(sPreloadHTML, dvId)
  {
  iconHeight=this.iconHeight;//icons related

  /*** Tetap Ada (For downgrade compatibility) ***/
  if(sPreloadHTML.substring(0,4)=="<!--" &&
    sPreloadHTML.substring(sPreloadHTML.length-3)=="-->")
    sPreloadHTML=sPreloadHTML.substring(4,sPreloadHTML.length-3);

  if(sPreloadHTML.substring(0,4)=="<!--" &&
    sPreloadHTML.substring(sPreloadHTML.length-6)=="--&gt;")
    sPreloadHTML=sPreloadHTML.substring(4,sPreloadHTML.length-6);

  /*** Converting back HTML-encoded content (kalau tdk encoded tdk masalah) ***/
  sPreloadHTML=sPreloadHTML.replace(/&lt;/g,"<");
  sPreloadHTML=sPreloadHTML.replace(/&gt;/g,">");
  sPreloadHTML=sPreloadHTML.replace(/&amp;/g,"&");

  /*** enable required buttons ***/
  if(this.cmdContentBlock!=";")this.btnContentBlock=true;
  if(this.cmdInternalLink!=";")this.btnInternalLink=true;
  if(this.cmdInternalImage!=";")this.btnInternalImage=true;
  if(this.cmdCustomObject!=";")this.btnCustomObject=true;
  if(this.arrCustomTag.length>0)this.btnCustomTag=true;
  if(this.mode=="HTMLBody"){this.btnXHTMLSource=true;this.btnXHTMLFullSource=false;}
  if(this.mode=="HTML"){this.btnXHTMLFullSource=true;this.btnXHTMLSource=false;}
  if(this.mode=="XHTMLBody"){this.btnXHTMLSource=true;this.btnXHTMLFullSource=false;}
  if(this.mode=="XHTML"){this.btnXHTMLFullSource=true;this.btnXHTMLSource=false;}
  
  /*** features ***/
  var bUseFeature=false;
  if(this.features.length>0)
    {
    bUseFeature=true;
    for(var i=0;i<this.buttonMap.length;i++)
      eval(this.oName+".btn"+this.buttonMap[i]+"=true");//ex: oEdit1.btnStyleAndFormatting=true (no problem), oEdit1.btn|=true (no problem), oEdit1.btnBRK=true (no problem)

    this.btnTextFormatting=false;this.btnListFormatting=false;
    this.btnBoxFormatting=false;this.btnParagraphFormatting=false;
    this.btnCssText=false;this.btnCssBuilder=false;
    for(var j=0;j<this.features.length;j++)
      eval(this.oName+".btn"+this.features[j]+"=true");//ex: oEdit1.btnTextFormatting=true

    for(var i=0;i<this.buttonMap.length;i++)
      {
      sButtonName=this.buttonMap[i];
      bBtnExists=false;
      for(var j=0;j<this.features.length;j++)
        if(sButtonName==this.features[j])bBtnExists=true;//ada;

      if(!bBtnExists)//tdk ada; set false
        eval(this.oName+".btn"+sButtonName+"=false");//ex: oEdit1.btnBold=false, oEdit1.btn|=false (no problem), oEdit1.btnBRK=false (no problem)
      }
    //Remove:"TextFormatting","ListFormatting",dst.=>tdk perlu(krn diabaikan)
    this.buttonMap=this.features;
    }
  /*** /features ***/

  this.preloadHTML=sPreloadHTML;
  var sHTMLDropMenus="";
  var sHTMLIcons="";
  var sTmp="";
  
  /*---------------*/
  /*Color picker   */
  /*---------------*/
  
  //Render Color Picker (forecolor)
  this.oColor1.url=this.scriptPath+"color_picker_fg.htm";
  this.oColor1.onShow = new Function(this.oName+".hide()");
  this.oColor1.onMoreColor = new Function(this.oName+".hide()");
  this.oColor1.onPickColor = new Function(this.oName+".applyColor('ForeColor',eval('"+this.oName+"').oColor1.color)");
  this.oColor1.onRemoveColor = new Function(this.oName+".applyColor('ForeColor','')");
  this.oColor1.txtCustomColors=getTxt("Custom Colors");
  this.oColor1.txtMoreColors=getTxt("More Colors...");

  //Render Color Picker (backcolor)
  this.oColor2.url=this.scriptPath+"color_picker_bg.htm";
  this.oColor2.onShow = new Function(this.oName+".hide()");
  this.oColor2.onMoreColor = new Function(this.oName+".hide()");
  this.oColor2.onPickColor = new Function(this.oName+".applyColor('BackColor',eval('"+this.oName+"').oColor2.color)");
  this.oColor2.onRemoveColor = new Function(this.oName+".applyColor('BackColor','')");
  this.oColor2.txtCustomColors=getTxt("Custom Colors");
  this.oColor2.txtMoreColors=getTxt("More Colors...");

  
  var me=this;

  if(this.tabs) {
    var tmp=null, tmpTb, grpMap=new Object();
    //create toolbar.
    for (var i=0;i<this.buttonMap.length;i++) {
      eval(this.oName+".btn"+this.buttonMap[i]+"=false");
    }
    for (var i=0; i<this.groups.length;i++) {
      tmp=this.groups[i];
      tmpTb=this.tbar.createToolbar(this.oName+"tbar"+tmp[0]);
      tmpTb.onClick=function(id) {tbAction(tmpTb, id, me, me.oName);};
      tmpTb.style.toolbar="main_istoolbar";
      tmpTb.iconPath=this.scriptPath+this.iconPath;
      tmpTb.btnWidth=this.iconWidth;
      tmpTb.btnHeight=this.iconHeight;
      
      for (var j=0;j<tmp[2].length;j++) {
        eval(this.oName+".btn"+tmp[2][j]+"=true");
      }
      buildToolbar(tmpTb, this, tmp[2]);
      grpMap[tmp[0]]=tmp[1];
    }
  
    //create tab
    var eTab=this.tbar.createTbTab("tabCtl"+this.oName), tmpGrp;
    for(var i=0; i<this.tabs.length; i++) {
      tmp=this.tabs[i];
      tmpGrp=this.tbar.createTbGroup(this.oName+"grp"+tmp[0]);
      for (var j=0; j<tmp[2].length;j++) {
        tmpGrp.addGroup(this.oName+tmp[2][j], grpMap[tmp[2][j]] , this.oName+"tbar"+tmp[2][j]);
      }
      eTab.addTab(this.oName+tmp[0], tmp[1], tmpGrp);
    }
  
  } else {
    var orTb=this.tbar.createToolbar(this.oName);  
      orTb.onClick=function(id) {tbAction(orTb, id, me, me.oName);};
      //orTb.style.toolbar="main_istoolbar";
      orTb.iconPath=this.scriptPath+this.iconPath;
      orTb.btnWidth=this.iconWidth;
      orTb.btnHeight=this.iconHeight;
      buildToolbar(orTb, this, this.buttonMap);
  }
  
  var sHTML="";

  sHTML+="<iframe name=idFixZIndex"+this.oName+" id=idFixZIndex"+this.oName+"  frameBorder=0 style='display:none;position:absolute;filter:progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)' src='"+this.scriptPath+"blank.gif' ></iframe>"; //src='javascript:;'
  sHTML+="<table id=idArea"+this.oName+" name=idArea"+this.oName+" border=0 "+
    "cellpadding=0 cellspacing=0 width='"+this.width+"' height='"+this.height+"'>"+
    "<tr><td colspan=2 style=\"position:relative;padding:0px;padding-left:0px;border:#cfcfcf 0px solid;border-bottom:0;background:url('"+this.scriptPath+"icons/bg.gif')\">"+
    "<table cellpadding=0 cellspacing=0 width='100%'><tr><td dir=ltr style='padding:0px'>"+
    this.tbar.render()+
    "</td></tr></table>"+
    "</td></tr>"+
    "<tr id=idTagSelTopRow"+this.oName+"><td colspan=2 id=idTagSelTop"+this.oName+" height=0 style='padding:0px'></td></tr>";

  sHTML+="<tr><td colspan=2 valign=top height=100% style='padding:0px;background:white'>";

  sHTML+="<table cellpadding=0 cellspacing=0 width=100% height=100%><tr><td width=100% height=100% style='padding:0px'>";//StyleSelect

  if(this.IsSecurityRestricted)
    sHTML+="<iframe security='restricted' style='width:100%;height:100%;' src='"+this.scriptPath+"blank.gif'"+
      " name=idContent"+ this.oName + " id=idContent"+this.oName+
      " contentEditable=true></iframe>";//prohibit running ActiveX controls
  else
    sHTML+="<iframe style='width:100%;height:100%;' src='"+this.scriptPath+"blank.gif'"+
      " name=idContent"+ this.oName + " id=idContent"+this.oName+
      " contentEditable=true></iframe>";

  //Paste From Word
  sHTML+="<iframe style='width:1px;height:1px;overflow:auto;' src='"+this.scriptPath+"blank.gif'"+
    " name=idContentWord"+ this.oName +" id=idContentWord"+ this.oName+
    " contentEditable=true onfocus='"+this.oName+".hide()'></iframe>";

  sHTML+="</td><td id=idStyles"+this.oName+" style='padding:0px;background:#E9E8F2' valign=top></td></tr></table>"//StyleSelect

  sHTML+="</td></tr>";
  sHTML+="<tr id=idTagSelBottomRow"+this.oName+"><td colspan=2 id=idTagSelBottom"+this.oName+" style='padding:0px;'></td></tr>";
  sHTML+="</table>";

  sHTML+=sHTMLDropMenus;//dropdown
  
  sHTML+="<input type=submit name=iwe_btnSubmit"+this.oName+" id=iwe_btnSubmit"+this.oName+" value=SUBMIT style='display:none' >";//hidden submit button

  if(dvId) {
    var edtStr=[];
    edtStr[0]=sHTML;
    document.getElementById(dvId).innerHTML=edtStr.join("");
  } else {
    document.write(sHTML);
  }
  
  var clPick=document.getElementById("isClPiker"+this.oName);
  if(!clPick) { 
    clPick = document.createElement("DIV");
    clPick.id="isClPiker"+this.oName;
    clPick.innerHTML=this.oColor1.generateHTML() + this.oColor2.generateHTML();
    document.body.insertBefore(clPick, document.body.childNodes[0]);
  }

  this.init();
  }

function initISEditor() {

  if(this.useTagSelector)
    {
    if(this.TagSelectorPosition=="bottom")this.TagSelectorPosition="top";
    else this.TagSelectorPosition="bottom";
    this.moveTagSelector()
    }

  //paste from word temp storage
  /*var oWord=eval("idContentWord"+this.oName);
  oWord.document.designMode="on";
  oWord.document.open("text/html","replace");
  oWord.document.write("<html><head></head><body></body></html>");
  oWord.document.close();
  oWord.document.body.contentEditable=true;*/

  oUtil.oName=this.oName;//default active editor
  oUtil.oEditor=eval("idContent"+this.oName);
  oUtil.oEditor.document.designMode="on";
  oUtil.obj=eval(this.oName);

  oUtil.arrEditor.push(this.oName);

  //Normalize button position if the editor is placed in relative positioned element
  eval("idArea"+this.oName).style.position="absolute";
  window.setTimeout("eval('idArea"+this.oName+"').style.position='';",1);

  var arrA = String(this.preloadHTML).match(/<HTML[^>]*>/ig);
  if(arrA)
    {//full html
    this.loadHTML("");
    //this.preloadHTML is required here. Can't use sPreloadHTML as in:
    //window.setTimeout(this.oName+".putHTML("+sPreloadHTML+")",0);
    window.setTimeout(this.oName+".putHTML("+this.oName+".preloadHTML)",0);
    //window.setTimeout utk fix swf loading.
    //Utk loadHTML & putHTML yg di SourceEditor tdk masalah
    }
  else
    {
    this.loadHTML(this.preloadHTML)
    }
}

function buildToolbar(tb, oEdt, btnMap) {
  var oName=oEdt.oName;
  
  for(var i=0;i<btnMap.length;i++)
    {
    sButtonName=btnMap[i];
    switch(sButtonName)
      {
      case "|":
        tb.addSeparator();
        break;
      case "BRK":
        tb.addBreak();
        break;
      case "Save":
        if(oEdt.btnSave)tb.addButton("btnSave"+oName, "btnSave.gif", getTxt("Save"));
        break;
      case "Preview":
          if(oEdt.btnPreview) {
            tb.addDropdownButton("btnPreview"+oName, "ddPreview"+oName, "btnPreview.gif",getTxt("Preview"));
            var pvDD=new ISDropdown("ddPreview"+oName); 
            pvDD.addItem("btnPreview1"+oName, "640x480");
            pvDD.addItem("btnPreview2"+oName, "800x600");
            pvDD.addItem("btnPreview3"+oName, "1024x768");
            pvDD.onClick=function(id) {ddAction(tb, id, oEdt, oEdt.oName)};
          }
        break;
      case "FullScreen":
        if(oEdt.btnFullScreen)tb.addButton("btnFullScreen"+oName, "btnFullScreen.gif",getTxt("Full Screen"));
        break;
      case "Print":
        if(oEdt.btnPrint)tb.addButton("btnPrint"+oName,"btnPrint.gif",getTxt("Print"));
        break;
      case "Search":
        if(oEdt.btnSearch)tb.addButton("btnSearch"+oName,"btnSearch.gif",getTxt("Search"));
        break;
      case "SpellCheck":
        if(oEdt.btnSpellCheck)
          {
          tb.addButton("btnSpellCheck"+oName, "btnSpellCheck.gif",getTxt("Check Spelling"));
          }
        break;
      case "StyleAndFormatting":
        if(oEdt.btnTextFormatting||oEdt.btnParagraphFormatting||oEdt.btnListFormatting||oEdt.btnBoxFormatting||oEdt.btnCssText||oEdt.btnCssBuilder) {
          tb.addDropdownButton("btnStyleAndFormat"+oName, "ddFormatting"+oName, "btnStyle.gif",getTxt("Styles & Formatting"));
          var ddFmt=new ISDropdown("ddFormatting"+oName);
          ddFmt.onClick=function(id) {ddAction(tb, id, oEdt, oEdt.oName)};
          if(oEdt.btnTextFormatting) ddFmt.addItem("btnTextFormatting"+oName, getTxt("Text Formatting")); 
          if(oEdt.btnParagraphFormatting) ddFmt.addItem("btnParagraphFormatting"+oName, getTxt("Paragraph Formatting"));
          if(oEdt.btnListFormatting) ddFmt.addItem("btnListFormatting"+oName, getTxt("List Formatting"));
          if(oEdt.btnBoxFormatting) ddFmt.addItem("btnBoxFormatting"+oName, getTxt("Box Formatting"));
          if(oEdt.btnCssText) ddFmt.addItem("btnCssText"+oName, getTxt("Custom CSS"));
          if(oEdt.btnCssBuilder) ddFmt.addItem("btnCssBuilder"+oName, getTxt("CSS Builder"));
        }
        break;
      case "Styles":
        if(oEdt.btnStyles)tb.addButton("btnStyles"+oName,"btnStyleSelect.gif",getTxt("Style Selection"));
        break;
      case "Paragraph":
        if(oEdt.btnParagraph)
          {
          tb.addDropdownButton("btnParagraph"+oName,"ddParagraph"+oName, oUtil.langDir+"/btnParagraph.gif",getTxt("Paragraph"), 77);
          var ddPar=new ISDropdown("ddParagraph"+oName);
          ddPar.onClick=function(id) {ddAction(tb, id, oEdt, oEdt.oName)};
          for(var j=0;j<oEdt.arrParagraph.length;j++)
            {
            ddPar.addItem("btnParagraph_"+j+oName, 
              "<"+oEdt.arrParagraph[j][1]+" style=\"\margin-bottom:4px\"  unselectable=on> "+
              oEdt.arrParagraph[j][0]+"</"+oEdt.arrParagraph[j][1]+">");
            }
          }
        break;
      case "FontName":
        if(oEdt.btnFontName)
          {
          tb.addDropdownButton("btnFontName"+oName,"ddFontName"+oName,oUtil.langDir+"/btnFontName.gif",getTxt("Font Name"),77);
          var ddFont=new ISDropdown("ddFontName"+oName);
          ddFont.onClick=function(id) {ddAction(tb, id, oEdt, oEdt.oName)};
          for(var j=0;j<oEdt.arrFontName.length;j++)
            {
            ddFont.addItem("btnFontName_"+j+oName, "<span style='font-family:"+oEdt.arrFontName[j]+"' unselectable=on>"+oEdt.arrFontName[j]+"</span><span unselectable=on style='font-family:tahoma'>("+ oEdt.arrFontName[j] +")</span>");
            }
          }
        break;
      case "FontSize":
        if(oEdt.btnFontSize)
          {
          tb.addDropdownButton("btnFontSize"+oName,"ddFontSize"+oName,oUtil.langDir+"/btnFontSize.gif",getTxt("Font Size"),60);
          var ddFs=new ISDropdown("ddFontSize"+oName);
          ddFs.onClick=function(id) {ddAction(tb, id, oEdt, oEdt.oName)};
          for(var j=0;j<oEdt.arrFontSize.length;j++)
            {
            ddFs.addItem("btnFontSize_"+j+oName, 
              "<font unselectable=on size=\""+oEdt.arrFontSize[j][1]+"\">"+
              oEdt.arrFontSize[j][0]+"</font>");
            }
          }
        break;
      case "Cut":
        if(oEdt.btnCut)tb.addButton("btnCut"+oName,"btnCut.gif",getTxt("Cut"));
        break;
      case "Copy":
        if(oEdt.btnCopy)tb.addButton("btnCopy"+oName,"btnCopy.gif",getTxt("Copy"));
        break;
      case "Paste":
        if(oEdt.btnPaste)tb.addButton("btnPaste"+oName,"btnPaste.gif",getTxt("Paste"));
        break;
      case "PasteWord":
        if(oEdt.btnPasteWord)tb.addButton("btnPasteWord"+oName,"btnPasteWord.gif",getTxt("Paste from Word"));
        break;
      case "PasteText":
        if(oEdt.btnPasteText)tb.addButton("btnPasteText"+oName,"btnPasteText.gif",getTxt("Paste Text"));
        break;
      case "Undo":
        if(oEdt.btnUndo)tb.addButton("btnUndo"+oName,"btnUndo.gif",getTxt("Undo"));
        break;
      case "Redo":
        if(oEdt.btnRedo)tb.addButton("btnRedo"+oName,"btnRedo.gif",getTxt("Redo"));
        break;
      case "Bold":
        if(oEdt.btnBold)tb.addToggleButton("btnBold"+oName,"",false,"btnBold.gif",getTxt("Bold"));
        break;
      case "Italic":
        if(oEdt.btnItalic)tb.addToggleButton("btnItalic"+oName,"",false,"btnItalic.gif",getTxt("Italic"));
        break;
      case "Underline":
        if(oEdt.btnUnderline)tb.addToggleButton("btnUnderline"+oName,"",false,"btnUnderline.gif",getTxt("Underline"));
        break;
      case "Strikethrough":     
        if(oEdt.btnStrikethrough)tb.addToggleButton("btnStrikethrough"+oName,"",false,"btnStrikethrough.gif",getTxt("Strikethrough"));
        break;
      case "Superscript":
        if(oEdt.btnSuperscript)tb.addToggleButton("btnSuperscript"+oName,"",false,"btnSuperscript.gif",getTxt("Superscript"));
        break;
      case "Subscript":
        if(oEdt.btnSubscript)tb.addToggleButton("btnSubscript"+oName,"",false,"btnSubscript.gif",getTxt("Subscript"));
        break;
      case "JustifyLeft":
        if(oEdt.btnJustifyLeft)tb.addToggleButton("btnJustifyLeft"+oName,"align",false,"btnLeft.gif",getTxt("Justify Left"));
        break;
      case "JustifyCenter":
        if(oEdt.btnJustifyCenter)tb.addToggleButton("btnJustifyCenter"+oName,"align",false,"btnCenter.gif",getTxt("Justify Center"));
        break;
      case "JustifyRight":
        if(oEdt.btnJustifyRight)tb.addToggleButton("btnJustifyRight"+oName,"align",false,"btnRight.gif",getTxt("Justify Right"));
        break;
      case "JustifyFull":
        if(oEdt.btnJustifyFull)tb.addToggleButton("btnJustifyFull"+oName,"align",false,"btnFull.gif",getTxt("Justify Full"));
        break;
      case "Numbering":
        if(oEdt.btnNumbering)tb.addToggleButton("btnNumbering"+oName,"bullet",false,"btnNumber.gif",getTxt("Numbering"));
        break;
      case "Bullets":
        if(oEdt.btnBullets)tb.addToggleButton("btnBullets"+oName,"bullet",false,"btnList.gif",getTxt("Bullets"));
        break;
      case "Indent":
        if(oEdt.btnIndent)tb.addButton("btnIndent"+oName,"btnIndent.gif",getTxt("Indent"));
        break;
      case "Outdent":
        if(oEdt.btnOutdent)tb.addButton("btnOutdent"+oName,"btnOutdent.gif",getTxt("Outdent"));
        break;
      case "LTR":
        if(oEdt.btnLTR)tb.addToggleButton("btnLTR"+oName,"dir",false,"btnLTR.gif",getTxt("Left To Right"));
        break;
      case "RTL":
        if(oEdt.btnRTL)tb.addToggleButton("btnRTL"+oName,"dir",false,"btnRTL.gif",getTxt("Right To Left"));
        break;
      case "ForeColor":
        if(oEdt.btnForeColor)tb.addButton("btnForeColor"+oName,"btnForeColor.gif",getTxt("Foreground Color"));
        break;
      case "BackColor":
        if(oEdt.btnBackColor)tb.addButton("btnBackColor"+oName,"btnBackColor.gif",getTxt("Background Color"));
        break;
      case "Bookmark":
        if(oEdt.btnBookmark)tb.addButton("btnBookmark"+oName,"btnBookmark.gif",getTxt("Bookmark"));
        break;
      case "Hyperlink":
        if(oEdt.btnHyperlink)tb.addButton("btnHyperlink"+oName,"btnHyperlink.gif",getTxt("Hyperlink"));
        break;
      case "CustomTag":
        if(oEdt.btnCustomTag)
          {
          tb.addDropdownButton("btnCustomTag"+oName,"ddCustomTag"+oName,oUtil.langDir+"/btnCustomTag.gif",getTxt("Tags"),60);
          var ddCustomTag=new ISDropdown("ddCustomTag"+oName);
          ddCustomTag.onClick=function(id) {ddAction(tb, id, oEdt, oEdt.oName)};
          for(var j=0;j<oEdt.arrCustomTag.length;j++)
            {
              ddCustomTag.addItem("btnCustomTag_"+j+oName, oEdt.arrCustomTag[j][0]);
            }
          }
        break;
      case "Image":
        if(oEdt.btnImage)tb.addButton("btnImage"+oName,"btnImage.gif",getTxt("Image"));
        break;
      case "Flash":
        if(oEdt.btnFlash)tb.addButton("btnFlash"+oName,"btnFlash.gif",getTxt("Flash"));
        break;
      case "Media":
        if(oEdt.btnMedia)tb.addButton("btnMedia"+oName,"btnMedia.gif",getTxt("Media"));
        break;
      case "ContentBlock":
        if(oEdt.btnContentBlock)tb.addButton("btnContentBlock"+oName,"btnContentBlock.gif",getTxt("Content Block"));
        break;
      case "InternalLink":
        if(oEdt.btnInternalLink)tb.addButton("btnInternalLink"+oName,"btnInternalLink.gif",getTxt("Internal Link"));
        break;
      case "InternalImage":
        if(oEdt.btnInternalImage)tb.addButton("btnInternalImage"+oName,"btnInternalImage.gif",getTxt("Internal Image"));
        break;
      case "CustomObject":
        if(oEdt.btnCustomObject)tb.addButton("btnCustomObject"+oName,"btnCustomObject.gif",getTxt("Object"));
        break;
      case "Table":
        if(oEdt.btnTable)
          {
          var sdd=[], sZ=0;
          sdd[sZ++]="<table width=195 id=dropTableCreate"+oName+" onmouseout='doOut_TabCreate();event.cancelBubble=true' style='cursor:default;background:#f3f3f8;border:#8a867a 1px solid;' cellpadding=0 cellspacing=2 border=0 unselectable=on>";
          for(var m=0;m<8;m++)
            {
            sdd[sZ++]="<tr>";
            for(var n=0;n<8;n++)
              {
              sdd[sZ++]="<td onclick='"+oName+".doClick_TabCreate()' onmouseover='doOver_TabCreate()' style='background:#ffffff;font-size:1px;border:#8a867a 1px solid;width:20px;height:20px;' unselectable=on>&nbsp;</td>";
              }
            sdd[sZ++]="</tr>";
            }
          sdd[sZ++]="<tr><td colspan=8 onclick=\""+oName+".hide();modelessDialogShow('"+oEdt.scriptPath+"table_insert.htm',300,322);\" onmouseover=\"this.innerText='"+getTxt("Advanced Table Insert")+"';this.style.border='#777777 1px solid';this.style.backgroundColor='#8d9aa7';this.style.color='#ffffff'\" onmouseout=\"this.style.border='#f3f3f8 1px solid';this.style.backgroundColor='#f3f3f8';this.style.color='#000000'\" align=center style='font-family:verdana;font-size:10px;font-color:black;border:#f3f3f8 1px solid;' unselectable=on>"+getTxt("Advanced Table Insert")+"</td></tr>";
          sdd[sZ++]="</table>";
          
          tb.addDropdownButton("btnTable"+oName,"ddTable"+oName,"btnTable.gif",getTxt("Insert Table"));
          var ddTable=new ISDropdown("ddTable"+oName);
          ddTable.add(new ISCustomDDItem("btnInsertTable", sdd.join("")));
          
                    
          tb.addDropdownButton("btnTableEdit"+oName,"ddTableEdit"+oName,"btnTableEdit.gif",getTxt("Edit Table/Cell"));
          var ddTblEdit=new ISDropdown("ddTableEdit"+oName);
          ddTblEdit.onClick=function(id) {ddAction(tb, id, oEdt, oEdt.oName)};
          ddTblEdit.addItem("mnuTableSize"+oName, getTxt("Table Size"));
          ddTblEdit.addItem("mnuTableEdit"+oName, getTxt("Edit Table"));
          ddTblEdit.addItem("mnuCellEdit"+oName, getTxt("Edit Cell"));
          }
        break;
      case "Guidelines":
        if(oEdt.btnGuidelines)tb.addButton("btnGuidelines"+oName,"btnGuideline.gif",getTxt("Show/Hide Guidelines"));
        break;
      case "Absolute":
        if(oEdt.btnAbsolute)tb.addButton("btnAbsolute"+oName,"btnAbsolute.gif",getTxt("Absolute"));
        break;
      case "Characters":
        if(oEdt.btnCharacters)tb.addButton("btnCharacters"+oName,"btnSymbol.gif",getTxt("Special Characters"));
        break;
      case "Line":
        if(oEdt.btnLine)tb.addButton("btnLine"+oName,"btnLine.gif",getTxt("Line"));
        break;
      case "Form":
        if(oEdt.btnForm)
          {
          var arrFormMenu = [[getTxt("Form"),"form_form.htm","280","177"],
            [getTxt("Text Field"),"form_text.htm","285","289"],
            [getTxt("List"),"form_list.htm","295","332"],
            [getTxt("Checkbox"),"form_check.htm","235","174"],
            [getTxt("Radio Button"),"form_radio.htm","235","177"],
            [getTxt("Hidden Field"),"form_hidden.htm","235","152"],
            [getTxt("File Field"),"form_file.htm","235","132"],
            [getTxt("Button"),"form_button.htm","235","174"]];
          
          tb.addDropdownButton("btnForm"+oName, "ddForm"+oName, "btnForm.gif",getTxt("Form Editor"));
          var ddForm=new ISDropdown("ddForm"+oName);
          ddForm.onClick=function(id) {ddAction(tb, id, oEdt, oEdt.oName)};
          for(var j=0;j<arrFormMenu.length;j++)
            {
              ddForm.addItem("btnForm"+j+oName, arrFormMenu[j][0]);
            }
          }
        break;
      case "RemoveFormat":
        if(oEdt.btnRemoveFormat)tb.addButton("btnRemoveFormat"+oName,"btnRemoveFormat.gif",getTxt("Remove Formatting"));
        break;
      case "HTMLFullSource":
        if(oEdt.btnHTMLFullSource)tb.addButton("btnHTMLFullSource"+oName,"btnSource.gif",getTxt("View/Edit Source"));
        break;
      case "HTMLSource":
        if(oEdt.btnHTMLSource)tb.addButton("btnHTMLSource"+oName,"btnSource.gif",getTxt("View/Edit Source"));
        break;
      case "XHTMLFullSource":
        if(oEdt.btnXHTMLFullSource)tb.addButton("btnXHTMLFullSource"+oName,"btnSource.gif",getTxt("View/Edit Source"));
        break;
      case "XHTM