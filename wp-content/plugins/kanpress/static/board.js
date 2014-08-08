/**
 * The Kanpress namespace
 */
var Kanpress = {};

( function( $ ) {

	/**
	 * Default options for jQuery UI Dialog
	 */
	Kanpress.getDialogDefaults = function() {
		var ret = {
			'autoOpen'			: false, 
			'closeOnEscape'	: true,	
			'draggable'			: false,			 
			'dialogClass'		: 'wp-dialog',					 
			'modal'					: true,
			'resizable'			: true,
			'width'					: 'auto',
			'height'				: 'auto',
			'buttons'				: {}
		};
		
		return ret;
	}

	/**
	 * Currently editing task form
	 */
	Kanpress.$editing = null;

	/**
	 * Board height = page height minus a little bit...
	 */
	Kanpress.fitBoard = function() {

		//Since WP 3.5 #footer is renamed to #wpfooter
		$footer = $( '#wpfooter' ).length ? $( '#wpfooter' ) : $( '#footer' );

		var height = $footer.position().top - $footer.height() - $( '.tasks-area:eq(0)' ).position().top - 50;
		$( '.tasks-area' ).css( 'min-height', height + 'px' );

		//All .tasks-area must have the same height
		/** @todo Revisar si esto es necesario... quizá se puede asignar height a todas directamente */
		var tallest = 0;
		columns = $( '.tasks-area' );
		for ( i in columns ) {
				if ( typeof i === 'number' ) {
					height = $( columns[ i ] ).height();
					tallest = ( tallest > height ) ? tallest : height;
				}
		}
		$( '.tasks-area' ).height( tallest );

		//Responsive layout
		if ( $( window ).width() < 700) {
				/** @todo Cambiar <h3> "artículos planteados" => "planteados", "pendiente de revisión"=>"pendiente" */
				$( '.wrap' ).addClass( 'responsive-pq' );
		} else {
				$( '.wrap' ).removeClass( 'responsive-pq' );
		}
	};


	/**
		* Task counter on top of each column
		*/
	Kanpress.countTasks = function() {
		$( '#col1, #col2, #col3' ).each(function( n, columna ) {
			var $tasks = $( columna ).find( '.tarea' ).length;
			$( columna ).find( 'h3 span' ).html( '(' + $tasks + ')' );
		} );
	};


	/**
		* Show the new task form pop-up
		*
		* Si el argumento noVaciarFormulario es true, se mantiene y no se vacía. Si es
		* cualquier otro valor, o no se pasa, se vaciará el formulario.
		*/
	Kanpress.showPopupNewTask = function( noVaciarFormulario ) {

		if ( !Kanpress.hasOwnProperty( '$newTask' ) ) {

			var dialogOptions = Kanpress.getDialogDefaults();
			dialogOptions.title = KanpressData.l10n[ 'Propose new article' ];
			
			/** @todo Dejarse de mierdas y buscar la forma de meter un submit tradicional en los buttons del Dialog */
			dialogOptions.buttons[ KanpressData.l10n[ 'Propose new article' ] ] = function() {
				$( '.form-nueva' )
					.submit();
			};

			Kanpress.$newTask = $( '#form-nueva' );
			Kanpress.$newTask.dialog( dialogOptions );
		}

		Kanpress.$newTask.dialog( 'open' );

		//Empty the form when validation failed
		if ( !( noVaciarFormulario == true ) ) {
			$( '.val' ).hide();
			$( '#resumen' ).val( '' );
			$( '#descripcion' ).val( '' );
			/** @todo Resetear valores de los <select> */
		}

		$( '#resumen' ).focus();
	};

	
	/**
	 * Show pop-up for removal confirm.
	 */
	Kanpress.deleteTask = function( self, selfDialog ) {
		
		//The element ID "remove-xxx" where xxx is the task ID.
		taskId = $( self ).attr( 'id' ).substr( 7 );

		$.post( KanpressData.baseUrl + '/ajax_remove_task.php', { task_id: taskId } );

		$( "#tarea-" + taskId ).hide( 'slow', function() {
				$( this ).remove();
		} );

		$( selfDialog ).dialog( 'close' );
		Kanpress.$editing.dialog( 'close' );
	};
	
	
	/**
	 * Show pop-up for removal confirm.
	 */
	Kanpress.showPopupDelete = function( self ) {

		if ( !Kanpress.hasOwnProperty( '$confirmRemoval' ) ) {

			Kanpress.$confirmRemoval = $( '<div class="kanpress-confirm-removal">'
				+ KanpressData.l10n[ 'Do you really want to remove this task?' ].replace( '\n', '<br>' )
				+ '</div>' ).appendTo( 'body' );
			
			var dialogOptions = Kanpress.getDialogDefaults();
			dialogOptions.title = KanpressData.l10n[ 'Propose new article' ];
			dialogOptions.buttons = {};
			
			dialogOptions.buttons[ KanpressData.l10n[ 'Confirm Removal' ] ] = function() {
				Kanpress.deleteTask( self, this );
			};
			
			Kanpress.$confirmRemoval.dialog( dialogOptions );
		}
		
		Kanpress.$confirmRemoval.dialog( 'open' );
	};
	
	
	/**
	 * Edit task: pop-up and actions
	 */
	Kanpress.showPopupEdit = function( self ) {
		
		var $self = $(self);
		
		idTarea = $self.data( 'id' );
		
		var dialogOptions = Kanpress.getDialogDefaults();
		dialogOptions.title = $self.html();
		dialogOptions.autoOpen = true;
		dialogOptions.width = 600;
		dialogOptions.buttons = {};	
	
		$taskForm = $( '#detalles-' + idTarea );
		$taskForm.dialog( dialogOptions );
		
		//We need it for deleteTask to hide the edit form after deleting
		Kanpress.$editing = $taskForm;

		$( '.remove-task-link' ).click( Kanpress.showPopupDelete );

		$( '.btn-guardar' ).click( function() {
				Kanpress.editTask( this );
		} );

		$( '.create-article' ).click( function() {
			Kanpress.createLinkedPost( this );
		} );
	};
	
	
	/**
	 * Update task card and AJAX
	 */
	Kanpress.editTask = function( self ) {
		
		var $self = $( self );
		
		var priorities = [ 'low', 'medium', 'high' ];

		var taskId = $self.attr( 'id' ).substr( 8 );
		var $formulario = $self.parent().parent();
		var description = $formulario.find( '.edit-description' ).val();
		var priority = $formulario.find( '.task-priority select' ).val();
		var category = $formulario.find( '.task-category select' ).val();

		//Show the new values in the task card...
		descriptionToShow = ( description.length > 100 )
			? description.substr(0, 100) + "..." 
			: description;

		$( '#short-' + taskId ).html( descriptionToShow );
		$( '#tarea-' + taskId ).find( 'h4' ).attr( 'class', priorities[ priority ] );
		categoryName = $formulario.find( '.task-category select option:selected' ).html();
		$( '#tarea-' + taskId ).find( '.seccion' ).html( categoryName );

		$( '#tarea-' + taskId ).find( '.edit-description' ).val( description );

		$( '#detalles-' + idTarea ).dialog( 'close' );

		//...and send them to the server via AJAX
		$.post( KanpressData.baseUrl + "/ajax_edit_task.php", {
			description	: description, 
			taskId			: taskId,
			priority		: priority,
			category		: category
		} );
	}
	
	
	/**
	 * AJAX and redirection
	 */
	Kanpress.createLinkedPost = function( self ) {
		
		$self = $( self );
		
		taskId = $self.attr( 'id' ).substr( 7 );

		/** @todo Sustituir elemento <a> por <span> */
		$self
			.css( 'text-decoration', 'none' )
			.html( '<img src="images/loading.gif" /> ' + KanpressData.l10n[ 'Creating...' ] );

		$.ajax( {
			type			: 'POST',
			dataType	: 'text',
			data			: { task_id: taskId },
			url				: KanpressData.baseUrl + '/ajax_link_task.php',

			success		: function( postId ) {
					//The HTTP response must be the linked post ID
					location.href = 'post.php?action=edit&post=' + postId;
			},
			error			: function() {
					/** @todo Handle 400 and 403 errors */
			}
		} );
	}
	
	
	/**
	 * Dialog for assigning a task
	 */
	Kanpress.showPopupAssign = function() {
		
		if ( !Kanpress.hasOwnProperty( '$dialogAssign' ) ) {
				 		
			Kanpress.$dialogAssign = $( '#asignar-tarea' );

			var dialogOptions = Kanpress.getDialogDefaults();
			dialogOptions.title = KanpressData.l10n[ 'Assign task' ];
			dialogOptions.buttons[ KanpressData.l10n[ 'Assign task' ] ] = function() {
				Kanpress.assignTask();
				Kanpress.$dialogAssign.dialog( 'close' );
			};

			Kanpress.$dialogAssign.dialog( dialogOptions );
		}

		Kanpress.$dialogAssign.dialog( 'open' );
		$( '#user' ).focus();

		//Pass the task ID to the form
		taskId = $( this ).parent().parent().attr( 'id' ).substr( 6 );
		$( '#taskId' ).val( taskId );
	};
	
	
	/**
	 * Assign task to user
	 */
	Kanpress.assignTask = function() {
		
		taskId = $( '#taskId' ).val();
		userId = $( '#user' ).val();

		postData = {
			'taskId': taskId,
			'user': userId
		};

		/** @todo Cambiar AJAX + reload por envío de formulario normal */
		$.ajax({
			type		: 'POST',
			url			: KanpressData.baseUrl + '/ajax_assign_task.php',
			dataType: 'html',
			data		: postData,
			success	: function( response ) {
				$( '#tarea-' + taskId + ' .asignar' ).html( response );

				//Update the details pop-up ("assigned to...")
				$( '#tarea-' + taskId + ' .asignacion' ).html(
						response + '<span class="light">' + KanpressData.l10n[ 'Asignada a' ] + '</span>'
						+ '<br />' + $( '#user option[value=' + userId + ']' ).html()
				);
			}
		} );
	};


	/**
	 * Set drag-and-drop for tasks with jQuery UI draggable and droppable
	 */
	Kanpress.setTaskDragAndDrop = function() {
		
		$( '.tarea' ).draggable( { distance: 20 } );

		$( '.tasks-area' ).droppable( {
			drop: function( event, ui ) {

				//When dropping on a column, set the position of the task to the flow
				$( '.ui-draggable-dragging' )
					.css( 'top', 0 )
					.css( 'left', 0 )
					.appendTo( $( this ) );

				var task = $( '.ui-draggable-dragging' );

				var taskId = parseInt( task.attr( 'id' ).substr( 6 ) );

				//Change status via AJAX
				var newStatus = parseInt( $( this ).parent().attr( 'id' ).substr( 3 ) ) - 1;
				$.post( KanpressData.baseUrl + '/ajax_move_task.php', { 
					task_id: taskId, 
					status: newStatus
				} );

				//Shine effect
				task.animate( { opacity: .4 }, 200, function() {
					task.animate( { opacity: 1 }, 200 );
				} );

				//Update the height of the columns
				$( '.tasks-area' ).css( 'height', 'auto' );
				Kanpress.fitBoard();

				Kanpress.countTasks();
			}
		} );
	};


	$( function() {

		Kanpress.fitBoard();
		$(window).resize( Kanpress.fitBoard );

		Kanpress.countTasks();

		Kanpress.setTaskDragAndDrop();

		//Triggered when form validation failed
		if ( KanpressData.showPopupOnLoad ) {
			Kanpress.showPopupNewTask( true );
		}

		/* 
			* General bindings 
			*/
		$( '.add-new-h2' ).click( Kanpress.showPopupNewTask );

		$( '.asignar' ).click( Kanpress.showPopupAssign );

		$( '.remove-task-link' ).click( function() {
			Kanpress.showPopupDelete( this );
		} );

		$( '.enlace-detalles' ).click( function() {
			Kanpress.showPopupEdit( this );
		} );

	} ); // end onLoad
} )( jQuery );