(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	

	$(document).ready(function() {
		jQuery('#skills').select2();
		jQuery('#education').select2();
		jQuery('#ratings').select2();

		// Initialize DataTable
		var table = $('#profile_datas').DataTable({
			searching: false,
			info: false,
			"columnDefs": [
				{ "orderable": false, "targets": [0, 2, 3, 4, 5] },
				{ "orderable": true, "targets": [1] }
			],
			lengthMenu: [5, 10, 20, 50, 100, 200, 500],
			dom: 'Bfrtip',
			buttons: [
				{
					extend: 'excelHtml5',
					text: 'Save as Excel',
					customize: function( xlsx ) {
						var sheet = xlsx.xl.worksheets['sheet1.xml'];
						$('row:first c', sheet).attr( 's', '42' );
					}
				}
			],
			"pageLength": 5
		});
	  
		// Function to handle the form submit event
		$('#profile-search-form').on('submit', function(e) {
			e.preventDefault(); // Prevent the form from submitting normally
		
			// Get the form values
			var skills = $('#skills').val();
			var keyword = $('#keyword').val();
			var education = $('#education').val();
			var age = $('#age').val();
			var ratings = $('#ratings').val();
			var jobs_completed = $('#jobs_completed').val();
			var years_experience = $('#years_experience').val();
			var sorting = $('#sorting').val();
			var wpnonce = jQuery('#pl_multidots_search_nonce').val();
			var httprefer = jQuery('[name="_wp_http_referer"]').val();
	  
		  	// Perform AJAX request to fetch filtered data
		  	$.ajax({
				url: myAjax.ajaxurl, // Replace with the actual AJAX URL
				method: 'POST',
				data: {
					action: 'profile_search_datatable',
					skills: skills,
					keyword: keyword,
					education: education,
					age: age,
					ratings: ratings,
					jobs_completed: jobs_completed,
					years_experience: years_experience,
					sorting: sorting,
					pl_multidots_search_nonce: wpnonce,
					_wp_http_referer: httprefer
				},
				beforeSend: function () {
					$('#loader').removeClass('display-none');
				},
				success: function(response) {
					console.log(response.data);
					if(response.data == undefined){
						$('#loader').addClass('display-none');
					}
					$(".profile-listing-wrap").show();
					// Update the DataTable with the filtered data
					//table.clear().rows.add(response.data).draw();
					table.clear().draw();
					table.rows.add(response.data); // Add new data
					table.columns.adjust().draw(); // Redraw the DataTable

					downloadDataToexcell();
				},
				complete: function() {
					$('#loader').addClass('display-none');
				},
				error: function(xhr, status, error) {
					// Handle error
					$('#profile-listing').html('<p class="error">Error: ' + xhr.status + ' ' + thrownError + '</p>');
				}
		  	});
		});

		downloadDataToexcell();
	});	  

	function downloadDataToexcell() {
		jQuery('body').on('click', '#export-profiles', function(e) {
			$('<table><thead><tr><th>No.</th><th>Profile Name</th><th>Age</th><th>Years of Experience</th><th>No of Jobs Completed</th><th>Ratings</th></tr></thead>')
			.append(
				$("#profile_datas").DataTable().$('tr').clone()
			)
			.table2excel({
				exclude: ".noExl",
				name: "Profiles Data",
				filename: "user_profiles"
			});
		});
	}

})( jQuery );
