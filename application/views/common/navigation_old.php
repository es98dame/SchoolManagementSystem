<!-- Fixed navbar -->
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">ALI</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><?=anchor('aboutus/welcome', 'WELCOME', null )?></li>
                        <li><?=anchor('aboutus/tuition', 'TUITION', null )?></li>
                        <li><?=anchor('aboutus/staff', 'STAFF', null )?></li>
                        <li><?=anchor('aboutus/calendar', 'CALENDAR', null )?></li>
                        <li><?=anchor('aboutus/webmail', 'WEBMAIL', null )?></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admission <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><?=anchor('admission/livinginus', 'Students living in the US', null )?></li>
                        <li><?=anchor('admission/outofus', 'Students out of US', null )?></li>
                        <li><?=anchor('admission/downlaod', 'Downloads', null )?></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Academics <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><?=anchor('academics/overview', 'Program Overview', null )?></li>
                        <li><?=anchor('academics/courses', 'Course Description', null )?></li>
                        <li><?=anchor('academics/schedule', 'Class Schedule', null )?></li>
                        <li><?=anchor('academics/instructors', 'Instructors', null )?></li>
                        <li><?=anchor('academics/handbooks', 'Student Handbook', null )?></li>
                        <li><?=anchor('academics/brochures', 'ALI Brochure', null )?></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Support <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><?=anchor('support/studentresource', 'Student Resource', null )?></li>
                        <li><?=anchor('support/immigration', 'Visa/Immigration Support', null )?></li>
                        <li><?=anchor('support/questions', 'Common Questions', null )?></li>
                        <li><?=anchor('support/exitsurvey', 'Exit Survey', null )?></li>
                    </ul>
                </li>
                <li><?=anchor('eagles/newsletter', 'The Eagles', null )?></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Student life <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><?=anchor('life/aboutdfw', 'ABOUT DFW', null )?></li>
                        <li><?=anchor('http://www.visitdallas.com/visitors/shopping/spotlight_on_shops/', 'SHOPPING', array('target'=>'_blank') )?></li>
                        <li><?=anchor('http://www.visitdallas.com/visitors/arts__entertainment/arts__culture/', 'ARTS', array('target'=>'_blank') )?></li>
                        <li><?=anchor('http://www.visitdallas.com/visitors/restaurants/spotlight_on_dining/', 'DINING', array('target'=>'_blank') )?></li>
                        <li><?=anchor('http://www.visitdallas.com/visitors/arts__entertainment/spotlight_on_fun/arts-entertainment/', 'ENTERTAINMENT', array('target'=>'_blank') )?></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bulletines<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><?=anchor('bulletines/alinews', 'ALI news', null )?></li>
                        <li><?=anchor('bulletines/qna', 'Q & A', null )?></li>
                        <li><?=anchor('bulletines/gallery', 'Photo Gallery', null )?></li>
                        <li><?=anchor('bulletines/suggestions', 'Suggestions', null )?></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>