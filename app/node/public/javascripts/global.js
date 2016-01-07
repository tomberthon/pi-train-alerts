// DOM Ready =============================================================
$(document).ready(function() {

  // Populate the table on initial page load
  populateTiles();

  setInterval(function() {
    populateTiles();
  }, 10000);

  // Set the time to now
  displayTime();

  setInterval(function() {
    displayTime();
  }, 1000);

});

// Functions =============================================================

// Fill table with data
function populateTable() {

  // Empty content string
  var tableContent = '';
  
  var url = apiHost + '/' + station + '/departures?size=6&destination=' + encodeURIComponent(destination);

  // jQuery AJAX call for JSON
  $.getJSON(url, function(data) {

    // For each item in our JSON, add a table row and cells to the content string
    $.each(data, function() {

      var time = this.std;
      var status = this.etd;
      var statusClass = 'none';

      if (this.etd === "Delayed" || this.etd === "Cancelled") {
        statusClass = 'warn'
        time = this.std + ' - ' + this.etd;
      } else if (this.etd !== "On time") {
        time = this.std + ' due ' + this.etd;
      }

      if (this.plannedCoaches !== this.actualCoaches) {
        status = this.actualCoaches + ' of ' + this.plannedCoaches + ' coaches';
        statusClass = 'info';
      } else if (this.isOldTrain) {
        status = 'Old stock';
        statusClass = 'info';
      }
      
      tableContent += '<tr>';
      tableContent += '<td>' + time + '</td>';
      tableContent += '<td>' + this.destination + '</td>';
      tableContent += '<td class="' + statusClass + '">' + status + '</td>';
      tableContent += '</tr>';
    });

    // Inject the whole content string into our existing HTML table
    $('#userList table tbody').html(tableContent);
  });
}

function displayTime() {
  var d = new Date();
  var pad = "00";
  var time = (pad + d.getHours()).slice(-pad.length) + ":" + (pad + d.getMinutes()).slice(-pad.length);
  $('#time').html(time);
}


function populateTiles() {
    // Empty content string
  var tileContent = '';
  
  var url = apiHost + '/' + station + '/departures?size=8&destination=' + encodeURIComponent(destination);

  // jQuery AJAX call for JSON
  $.getJSON(url, function(data) {

    // For each item in our JSON, add a table row and cells to the content string
    $.each(data, function() {

      var time = this.std;
      var status = this.etd;
      var statusClass = 'success';
      var destination = this.destination;

      if (this.etd === "Delayed" || this.etd === "Cancelled") {
        time = this.std;
        status = 'due ' + this.etd;
        statusClass = 'danger';
      } else if (this.etd !== "On time") {
        time = this.std;
        status = 'due ' + this.etd;
        statusClass = 'warning';
      }

      if (this.plannedCoaches !== this.actualCoaches) {
        status = this.actualCoaches + ' of ' + this.plannedCoaches + ' coaches';
        statusClass = 'info';
      } else if (this.isOldTrain) {
        status = 'Old stock';
        statusClass = 'info';
      }
      
      
      if (destination.length > 10) {
        destination = destination.substr(0, 10) + '...';
      }
      
      tileContent += '<span class="label label-' + statusClass + '">';
      tileContent += '<div class="time">' + time + '</div>';
      tileContent += '<div class="info-message">' + status + '<br />' + destination + '</div>';
      tileContent += '</span>';
    });
    
    // Inject the whole content string into our existing HTML table
    $('#tileList').html(tileContent);
  });
  
}