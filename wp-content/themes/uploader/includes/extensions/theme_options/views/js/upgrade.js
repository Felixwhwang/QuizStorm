( function( window, $, undefined ){
	"use strict"

	var upgrader = function()
	{
		this.section = $('.exc-tasks');
		this.secret_key = this.section.data('secret-key');
		this.tasks = this.section.find('.exc-task-list > li');
		//this.allTasksButton = $('#exc-run-tasks');
		this.taskButton = this.tasks.find('.action-buttons a');

		this.activeTask;

		this.bindEvents();
	}

	$.extend( upgrader.prototype, {

		// runAllTasks: function(e)
		// {
		// 	e.preventDefault();

		// 	var self = e.data.self,
		// 		tasks = [];

		// 	$.each( self.tasks, function( index, task ){

		// 		var taskID = $( this ).data('task');

		// 		if ( taskID )
		// 		{
		// 			tasks.push( taskID );
		// 		}
		// 	});

		// 	self.execute( tasks );
		// },

		runTask: function(e)
		{
			var self = e.data.self,
				$this = $(this),
				activeTask = $this.parents('li:first'),
				taskID = activeTask.data('task'),
				action = $( this ).data('action-id');

			if ( $this.attr('href') === '#' )
			{
				e.preventDefault();

				self.activeTask = activeTask;

				self.execute( [{task_id: taskID, action_id: action}] );
			}
		},

		execute: function( tasks, index )
		{
			index = index || 0;

			var self = this,
				spinner = self.activeTask.find('.spinner');

			self.activeTask.find('.task-notice').remove();
			
			spinner.addClass('is-active');

			if ( 'undefined' !== typeof tasks[ index ] )
			{
				var data = $.extend({}, tasks[ index ], {action: 'exc-theme-upgrade-task', secret_key: self.secret_key } );
		
				$.post( ajaxurl, data, function(r){
					
					if ( ! r.success )
					{
						if ( 'undefined' !== typeof r.data )
						{
							self.activeTask.prepend( r.data );
						}

						//self.activeTask.find('.task-details').wrap('<strike/>');
						// if ( ( index + 1 ) < tasks.length )
						// {
						// 	self.execute( tasks, ( index + 1 ) );
						// }
					} else 
					{
						self.activeTask.addClass('task-done');
						self.activeTask.find('.action-buttons').remove();

						if ( 'undefined' !== typeof r.data.redirect_to )
						{
							window.location = r.data.redirect_to;
						}						
					}

					spinner.removeClass('is-active');
				});
			}
		},

		bindEvents: function()
		{
			//this.allTasksButton.on( 'click', {self: this}, this.runAllTasks );
			this.taskButton.on( 'click', {self: this}, this.runTask );
		}
	});

	$( document ).ready( function(){
		window.testTask = new upgrader();
	});

})( window, jQuery );