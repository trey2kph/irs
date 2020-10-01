// JavaScript Document
/*
** CHANGE LOGS
**
** 2.0	- added functionality for preloaded comments
**		- removed {comments} object, just retrain the {page: _current} object
**		- fix the child <li> with class .commentPage_Set0 to retain its state when pagelink is click
** 2.1	- added recordset for pagination (e.g. 1 to 5 of 6)
**		- added viewAll at recordset object
**		- set this.options.total as the default value for _recordEnd
**		- hide pagination when show all comments is click
**		- added $.post for view all ajax
** 2.2	- set default page comments
**		- updated the .find('li'), changed it into .find('.comment') to avoid conflict with other ul
**		- created hash for default comment page
**		- added listHolderId
*/
(function($){
$.fn.Pagination = function (options) {
	this.options = $.extend({
		total: 0,
		numPerPage: 0 ,
		pagerHolderId: '' ,
		listHolderId: '.comment',
		pageSet: 2,
		xmlPath: '',
		defaultPage: 1,
		datas: '',
		afterLoad: '' , 
		recordset: {recordStart:'.recordStart', recordEnd:'.recordEnd', recordTotal:'.recordTotal', viewAll: '.showAll'}
	} , options || {});
		
	var TOTAL_PAGES = Math.ceil( this.options.total / this.options.numPerPage );
	var _listArr = {};
	var _temp = [];
	var _this = this.options;
	var _current = _this.defaultPage;
	var _my = this;
	var _recordStart;
	var _recordEnd = _this.total;
	var UI_paginate = function(e) {
		e.preventDefault();
		if ($(this).text() == 'First') _current = 1;
		else if ($(this).text() == 'Prev') _current--;
		else if ($(this).text() == 'Last') _current = TOTAL_PAGES;
		else if ($(this).text() == 'Next') _current++;
		else _current = $(this).text();
		var _currentSet = $(_my).find('.commentPage_Set'+_current);
		var _newPageNum = {page: _current};
		$.extend(true, _this.datas, _newPageNum);		
		$('#commentHashPointer').attr('name','comments-'+_current);
		if (_currentSet.length) {
			$(_my).find('.comment').not('.commentPage_Set0').css('display','none');
			$('.commentPagination').find('.uiPageNum').removeAttr('class');
			_currentSet.css('display','block');
			createPageNumber();
		} else {
			$.ajax({
				   url: _this.xmlPath ,
				   type: 'post' ,
				   data: _this.datas ,
				   beforeSend: function() {
					   $('#'+_this.pagerHolderId).prepend('<img src="'+IMAGEPATH+'loader16.gif" class="img_loader"> ');
				   } ,
				   success: function (html) {
					  $(_my).find('li').css('display','none');
					  $('.commentPagination').find('.uiPageNum').removeAttr('class');
					  $(_my).append(html);
					  createPageNumber();
					  if (typeof _this.afterLoad == 'function') _this.afterLoad.call(this,html,_current);
				   }
			});
		}
		_recordStart = ((_current-1)*_this.numPerPage) + 1;
		_recordEnd = _current==TOTAL_PAGES?_this.total:((_current-1)*_this.numPerPage)+_this.numPerPage;
		if ($(_this.recordset.recordStart).length) $(_this.recordset.recordStart).html(_recordStart);
		if ($(_this.recordset.recordEnd).length) $(_this.recordset.recordEnd).html(_recordEnd);
		getAjaxClicks(COMMENT_URL+'/comment', 'paginate');
		window.location.href = '#comments-'+_current;
		return;
	}
	
	var createPageNumber = function() {
		var _string = '';
		_string += '<ul class="commentPagination">';
		if (_current > 1) {
			_string += '<li><a href="" title="Go to First page">First</a></li>';
			_string += '<li><a href="" title="Go to Previous page">Prev</a></li>';
			_adjust  = -1;
		}

		var _start = ((_current - _this.pageSet) > 1) ? _current - (_this.pageSet - 1) : 1;
		var _end   = ((_current + _this.pageSet) < TOTAL_PAGES) ? _current + _this.pageSet : TOTAL_PAGES;
		
		for (i=_start;i<=_end;i++)
			_string += '<li><a class="uiPageNum'+((i==_current)?' current':'')+'" href="" title="Go to page '+i+'">'+i+'</a></li>';
		
		if (_current < TOTAL_PAGES) {
			_string += '<li><a href="" title="Go to Next page">Next</a></li>';
			_string += '<li><a href="" title="Go to Last page">Last</a></li>';
		}
		
		_string += '<ul>';
		
		$('#'+_this.pagerHolderId).html(_string);
		$(".commentPagination").find('a').bind('click',UI_paginate);
	}
	if ($(_this.recordset.viewAll).length) {
		var _viewall = $(_this.recordset.viewAll);
		_viewall.click(function() {
			_viewAttr = $(this).attr('name');
			var _setAttr = _viewAttr=='View_All_Comments'?'Show_Minimum_Comments':'View_All_Comments';
			var _setStart = 1;
			var _setEnd = _current==TOTAL_PAGES?_this.total:((_current-1)*_this.numPerPage)+_this.numPerPage;
			$(this).attr('name',_setAttr).html($(this).attr('name').replace('_',' '));
			if (_viewAttr=='View_All_Comments') {
				$('#commentList '+_this.listHolderId).not('commentPage_Set0').css({display: 'block'});
				$('#'+_this.pagerHolderId).css({display: 'none'});
				_setEnd = _this.total;
				var _totalDisplayed = $(_my).find(_this.listHolderId).not('.commentPage_Set0').length;
				if (!is_preloaded && _totalDisplayed!=_this.total) {
					var _newPageNum = {page: 0};
					$.extend(true, _this.datas, _newPageNum);
					$.post(_this.xmlPath, _this.datas,function(html) {
						$(_my).html(html);
						if (typeof _this.afterLoad == 'function') _this.afterLoad.call(this,html,_current);
					});
				}
			} else {
				$('#commentList '+_this.listHolderId).not('.commentPage_Set'+_current+', .commentPage_Set0').css({display: 'none'});
				$('#'+_this.pagerHolderId).css({display: 'block'});
				_setStart=_recordStart;
			}
			if ($(_this.recordset.recordStart).length) $(_this.recordset.recordStart).html(_setStart);
			if ($(_this.recordset.recordEnd).length) $(_this.recordset.recordEnd).html(_setEnd);

			return false;
		});
	}
	$(_my).find(_this.listHolderId).not('.commentPage_Set'+_current+' , .commentPage_Set0').css('display','none');
	createPageNumber();
	if (_current != 1) $(".commentPagination .current").trigger("click");
}
})(window.jQuery);