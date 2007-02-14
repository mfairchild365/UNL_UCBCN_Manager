<form action="?list=<?php echo $this->status; ?>" id="formlist" name="formlist" method="post">
<table class="eventlisting">
<thead>
<tr>
<th scope="col" class="select">Select</th>
<th scope="col" class="title"><a href="?list=<?php echo $_GET['list']; ?>&amp;orderby=title">Event Title</a></th>
<th scope="col" class="date"><a href="?list=<?php echo $_GET['list']; ?>&amp;orderby=starttime">Date</a></th>
<th scope="col" class="edit">Edit</th>
</tr>
</thead>
<tbody>
<?php
$oddrow = false;
foreach ($this->events as $e) {
	$row = '<tr id="row'.$e->id.'"';
	if (isset($_GET['new_event_id']) && $_GET['new_event_id']==$e->id) {
		if ($oddrow){
		$row .= ' class="updated alt"';
		} else{
		$row .= ' class="updated"';	
		}
	} elseif ($oddrow) {
		$row .= ' class="alt"';
	}
	$row .= ' onclick="highlightLine(this,'.$e->id.');">';
	$oddrow = !$oddrow;
	$row .=	'<td class="select"><input type="checkbox" onclick="checknegate('.$e->id.')" name="event'.$e->id.'" />' .
			'<td class="title">'.$e->title.'</td>' .
			'<td class="date">';
	$edt = UNL_UCBCN::factory('eventdatetime');
	$edt->event_id = $e->id;
	$edt->orderBy('starttime DESC');
	$instances = $edt->find();
	if ($instances) {
		$row .= '<ul>';
			while ($edt->fetch()) {
			    if (substr($edt->starttime,11) != '00:00:00') {
			        $row .= '<li>'.date('M jS g:ia',strtotime($edt->starttime)).'</li>';
			    } else {
			        $row .= '<li>'.date('M jS',strtotime($edt->starttime)).'</li>';
			    }
			}
		$row .= '</ul>';
    } else {
            $row .= 'Unknown';
    }
	$row .= '</td>' .
			'<td class="edit">';
	if (UNL_UCBCN::userCanEditEvent($_SESSION['_authsession']['username'],$e)) {
		$row .= '<a href="?action=createEvent&amp;id='.$e->id.'">Edit</a>';
	}
	$row .=		'</td></tr>';
	echo $row;
} ?>
</tbody>
</table>
<a href="#" class="checkall" onclick="setCheckboxes('formlist',true); return false">Check All</a>
<a href="#" class="uncheckall" onclick="setCheckboxes('formlist',false); return false">Uncheck All</a>
<input class="btnsubmit" id="delete_event" type="submit" name="delete" onclick="return confirm('Are you sure?');" value="Delete" />
<?php if ($this->status=='posted') { ?>
<input class="btnsubmit" id="moveto_pending" type="submit" name="pending" value="Move to Pending" />
<?php } elseif ($this->status=='pending') { ?>
<input class="btnsubmit" id="moveto_posted" type="submit" name="posted" value="Add to Posted" />
<?php } ?>
</form>