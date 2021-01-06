/* jce - 2.7.14 | 2019-06-11 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2019 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function(){function toAbsolute(u,p){return u.replace(/url\(["']?(.+?)["']?\)/gi,function(a,b){return b.indexOf("://")<0?'url("'+p+b+'")':a})}function isEditorContentCss(url){return url.indexOf("/tiny_mce/")!==-1&&url.indexOf("content.css")!==-1}var each=tinymce.each,DOM=tinymce.DOM;tinymce.create("tinymce.plugins.ImportCSS",{convertSelectorToFormat:function(selectorText){var format,ed=this.editor;if(selectorText){var selector=/^(?:([a-z0-9\-_]+))?(\.[a-z0-9_\-\.]+)$/i.exec(selectorText);if(selector){var elementName=selector[1];if("body"!==elementName){var classes=selector[2].substr(1).split(".").join(" "),inlineSelectorElements=tinymce.makeMap("a,img");return elementName?(format={title:selectorText},ed.schema.getTextBlockElements()[elementName]?format.block=elementName:ed.schema.getBlockElements()[elementName]||inlineSelectorElements[elementName.toLowerCase()]?format.selector=elementName:format.inline=elementName):selector[2]&&(format={inline:"span",selector:"*",title:selectorText.substr(1)}),ed.settings.importcss_merge_classes!==!1?format.classes=classes:format.attributes={class:classes},format}}}},populateStyleSelect:function(){var ed=this.editor,PreviewCss=tinymce.util.PreviewCss,self=this,styleselect=ed.controlManager.get("styleselect");if(styleselect&&!styleselect.hasClasses){var counter=styleselect.getLength(),selectors=this._import();0!==selectors.length&&(each(selectors,function(s,idx){var name="style_"+(counter+idx),fmt=self.convertSelectorToFormat(s);fmt&&(ed.formatter.register(name,fmt),styleselect.add(fmt.title,name,{style:function(){return PreviewCss(ed,fmt)}}))}),styleselect.hasClasses=!0)}},init:function(ed,url){this.editor=ed;var self=this;this.classes=[],this.fontface=[],ed.onPreInit.add(function(editor){var styleselect=ed.controlManager.get("styleselect");styleselect&&!styleselect.hasClasses&&ed.getParam("styleselect_stylesheets",!0)&&styleselect.onPostRender.add(function(ed,n){styleselect.NativeListBox?DOM.bind(DOM.get(n.id,"focus"),self.populateStyleSelect,self):(DOM.bind(DOM.get(n.id+"_text"),"focus mousedown",self.populateStyleSelect,self),DOM.bind(DOM.get(n.id+"_open"),"focus mousedown",self.populateStyleSelect,self))});var fontselect=ed.controlManager.get("fontselect");fontselect&&fontselect.onPostRender.add(function(){self.fontface.length&&self.classes.length||self._import()})}),ed.onNodeChange.add(function(){var styleselect=ed.controlManager.get("styleselect");if(styleselect&&!styleselect.hasClasses&&ed.getParam("styleselect_stylesheets",!0))return self.populateStyleSelect()})},_import:function(){function isAllowedStylesheet(href){var styleselect=ed.getParam("styleselect_stylesheets");return!styleselect||("undefined"!=typeof filtered[href]?filtered[href]:(filtered[href]=href.indexOf(styleselect)!==-1,filtered[href]))}function parseCSS(stylesheet){each(stylesheet.imports,function(r){if(r.href.indexOf("://fonts.googleapis.com")>0){var v="@import url("+r.href+");";return void(tinymce.inArray(self.fontface,v)===-1&&self.fontface.unshift(v))}parseCSS(r)});try{if(rules=stylesheet.cssRules||stylesheet.rules,href=stylesheet.href,!href)return;if(isEditorContentCss(href))return;stylesheet.href&&(href=stylesheet.href.substr(0,stylesheet.href.lastIndexOf("/")+1))}catch(e){}each(rules,function(r){switch(r.type||1){case 1:if(!isAllowedStylesheet(stylesheet.href))return!0;!r.type,r.selectorText&&each(r.selectorText.split(","),function(v){v=v.replace(/^\s*|\s*$|^\s\./g,""),!/\.mce/.test(v)&&/\.[\w\-]+$/.test(v)&&tinymce.inArray(self.classes,v)===-1&&self.classes.push(v)});break;case 3:if(r.href.indexOf("//fonts.googleapis.com")>0){var v="@import url("+r.href+");";tinymce.inArray(self.fontface,v)===-1&&self.fontface.unshift(v)}r.href.indexOf("//")===-1&&parseCSS(r.styleSheet);break;case 5:if(r.cssText&&/(fontawesome|glyphicons|icomoon)/i.test(r.cssText)===!1){var v=toAbsolute(r.cssText,href);tinymce.inArray(self.fontface,v)===-1&&self.fontface.push(v)}}})}var fontface,self=this,ed=this.editor,doc=ed.getDoc(),href=(ed.settings.class_filter,""),rules=[],filtered={};if(0===self.classes.length)try{each(doc.styleSheets,function(styleSheet){parseCSS(styleSheet)})}catch(ex){}if(self.fontface.length&&!fontface)try{var head=DOM.doc.getElementsByTagName("head")[0],style=DOM.create("style",{type:"text/css"}),css=self.fontface.join("\n");if(style.styleSheet){var setCss=function(){try{style.styleSheet.cssText=css}catch(e){}};style.styleSheet.disabled?setTimeout(setCss,10):setCss()}else style.appendChild(DOM.doc.createTextNode(css));head.appendChild(style),fontface=!0}catch(e){}return ed.getParam("styleselect_sort",1)&&self.classes.sort(),self.classes}}),tinymce.PluginManager.add("importcss",tinymce.plugins.ImportCSS)}();