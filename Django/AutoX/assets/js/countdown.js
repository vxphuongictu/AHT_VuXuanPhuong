/* Time ago */
var MONTH_NAMES = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
];


function getFormattedDate(date, prefomattedDate = false, hideYear = false) {
  const day 	  = date.getDate();
  const month 	= MONTH_NAMES[date.getMonth()];
  const year 	  = date.getFullYear();
  const hours 	= date.getHours();
  let minutes 	= date.getMinutes();

  if (minutes < 10) {
    // Adding leading zero to minutes
    minutes = `0${ minutes }`;
  }

  if (prefomattedDate) {
    // Today at 10:20
    // Yesterday at 10:20
    // return `${ prefomattedDate } at ${ hours }:${ minutes }`;
    return `${ prefomattedDate }`;
  }

  if (hideYear) {
    // 10. January at 10:20
    // return `${ day } ${ month } at ${ hours }:${ minutes }`;
    return `${ day } ${ month }`;
  }

  // 10. January 2017. at 10:20
  // return `${ day } ${ month } ${ year } at ${ hours }:${ minutes }`;
  return `${ day } ${ month } ${ year }`;
}


// --- Main function
function timeAgo(product_id, dateParam) {
  if (!dateParam) {
    var result    = null;
  }

  const date        = typeof dateParam === 'object' ? dateParam : new Date(dateParam);
  const DAY_IN_MS   = 86400000; // 24 * 60 * 60 * 1000
  const today       = new Date();
  const yesterday   = new Date(today - DAY_IN_MS);
  const seconds     = Math.round((today - date) / 1000);
  const minutes     = Math.round(seconds / 60);
  const isToday     = today.toDateString() === date.toDateString();
  const isYesterday = yesterday.toDateString() === date.toDateString();
  const isThisYear  = today.getFullYear() === date.getFullYear();


  if (seconds < 5) {
    result          = 'now';
  } else if (seconds < 60) {
    result          = `${ seconds } seconds ago`;
  } else if (seconds < 90) {
    result          = 'about a minute ago';
  } else if (minutes < 60) {
    result          =  `${ minutes } minutes ago`;
  } else if (isToday) {
    result          = getFormattedDate(date, 'Today'); // Today at 10:20
  } else if (isYesterday) {
    result          = getFormattedDate(date, 'Yesterday'); // Yesterday at 10:20
  } else if (isThisYear) {
    result          = getFormattedDate(date, false, true); // 10. January at 10:20
  }

  result  = getFormattedDate(date); // 10. January 2017. at 10:20
  $('#add-time-'+product_id).html(result);
}

/* Count down */
function makeTimer(id, date_time) {
	var endTime = new Date(date_time);
	endTime = (Date.parse(endTime) / 1000);

	var now = new Date();
	now = (Date.parse(now) / 1000);

	var timeLeft = endTime - now;

	var days = Math.floor(timeLeft / 86400);
	var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
	var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600 )) / 60);
	var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));

	if (hours < "10") { hours = "0" + hours; }
	if (minutes < "10") { minutes = "0" + minutes; }
	if (seconds < "10") { seconds = "0" + seconds; }

	if (days < 0)
	{
		$("#days-"+id).html("<span style='color: red;'> finished</span>");
	} else {
		$("#days-"+id).html(days + "<span> days</span>");
		$("#hours-"+id).html(hours + "<span> hours</span>");
		$("#minutes-"+id).html(minutes + "<span> minutes</span>");
	}
}