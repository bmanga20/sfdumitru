/* JCE Editor - 2.3.4.3 | 11 December 2013 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
var WFFileBrowser={settings:{},element:'',init:function(element,options){$.extend(true,this.settings,options);this.element=element;this._createBrowser();},_createBrowser:function(){$(this.element).MediaManager(this.settings);},getBaseDir:function(){return this._call('getBaseDir');},getCurrentDir:function(){return this._call('getCurrentDir');},getSelectedItems:function(key){return this._call('getSelectedItems',key);},setSelectedItems:function(items){return this._call('setSelectedItems',items);},refresh:function(){return this._call('refresh');},error:function(error){return this._call('error',error);},status:function(message,state){return this._call('setStatus',{message:message,state:state});},load:function(items){return this._call('load',items);},resize:function(fh){return this._call('resize',[null,fh]);},startUpload:function(){return this._call('startUpload');},stopUpload:function(){return this._call('stopUpload');},setUploadStatus:function(message,state){return this._call('setUploadStatus',{message:message,state:state});},get:function(fn,args){return this._call(fn,args);},_call:function(fn,args){return $(this.element).MediaManager(fn,args);}};