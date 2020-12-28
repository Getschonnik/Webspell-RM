<?php
/*-----------------------------------------------------------------\
| _    _  ___  ___  ___  ___  ___  __    __      ___   __  __       |
|( \/\/ )(  _)(  ,)/ __)(  ,\(  _)(  )  (  )    (  ,) (  \/  )      |
| \    /  ) _) ) ,\\__ \ ) _/ ) _) )(__  )(__    )  \  )    (       |
|  \/\/  (___)(___/(___/(_)  (___)(____)(____)  (_)\_)(_/\/\_)      |
|                       ___          ___                            |
|                      |__ \        / _ \                           |
|                         ) |      | | | |                          |
|                        / /       | | | |                          |
|                       / /_   _   | |_| |                          |
|                      |____| (_)   \___/                           |
\___________________________________________________________________/
/                                                                   \
|        Copyright 2005-2018 by webspell.org / webspell.info        |
|        Copyright 2018-2019 by webspell-rm.de                      |
|                                                                   |
|        - Script runs under the GNU GENERAL PUBLIC LICENCE         |
|        - It's NOT allowed to remove this copyright-tag            |
|        - http://www.fsf.org/licensing/licenses/gpl.html           |
|                                                                   |
|               Code based on WebSPELL Clanpackage                  |
|                 (Michael Gruber - webspell.at)                    |
\___________________________________________________________________/
/                                                                   \
|                     WEBSPELL RM Version 2.0                       |
|           For Support, Mods and the Full Script visit             |
|                       webspell-rm.de                              |
\------------------------------------------------------------------*/

$_language->readModule('profile');

if (isset($_GET[ 'id' ])) {
    $id = (int)$_GET[ 'id' ];
} else {
    $id = $userID;
}
if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if (isset($id) && getnickname($id) != '' && deleteduser($id) == '0') {
	
    if (isbanned($id)) {
        $banned =
            '' . $_language->module[ 'is_banned' ] . '';
    } else {
        $banned = '';
    }	

   
    //profil: home
    
        $data_array = array();
        $data_array['$id'] = $id;
        $data_array['$banned'] = $banned;
        
        $data_array['$profile'] = $_language->module[ 'profile' ];
        $template = $tpl->loadTemplate("profile","head", $data_array);
        echo $template;
    
        $date = time();
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "user WHERE userID='" . $id . "'");
        $anz = mysqli_num_rows($ergebnis);
        $ds = mysqli_fetch_array($ergebnis);

       
        if ($ds[ 'userpic' ]) {
            $userpic = '<img class="image-responsive img-circle userpic-wh" src="images/userpics/' . $ds[ 'userpic' ] . '" alt="">';
			$profile_bg = '<img class="card-bkimg" src="images/userpics/' . $ds[ 'userpic' ] . '" alt="">';
        } else {
            $userpic = '<img class="image-responsive" src="images/userpics/nouserpic.png" alt="">';
			$profile_bg = '<img class="image-responsive" src="images/userpics/nouserpic.png" alt="">';
        }
        $nickname = $ds[ 'nickname' ];
        if (isclanmember($id)) {
            $member = ' <i class="fa fa-user" style="color: #5cb85c"></i> '.$_language->module[ 'clanmember' ].' ';
        } else {
            $member = '';
        }
        
        $registered = getformatdatetime($ds[ 'registerdate' ]);
        $lastlogin = getformatdatetime($ds[ 'lastlogin' ]);
        
        if ($ds[ 'avatar' ]) {
            $avatar = '<img src="images/avatars/' . $ds[ 'avatar' ] . '" alt="">';
        } else {
            $avatar = '<img src="images/avatars/noavatar.png" alt="">';
        }
        if(isonline($ds[ 'userID' ])=="offline") {
		  $status = '<span class="label label-danger">offline</span>';
		} else {
		  $status = '<span class="label label-success">online</span>';
		}

        if ($ds[ 'email_hide' ]) {
            $email = $_language->module[ 'n_a' ];
        } else {
            $email = '<a class="label label-danger" href="mailto:' . mail_protect(cleartext($ds[ 'email' ])) .
                '"><i class="fa fa-envelope" title="' . $_language->module[ 'iemail' ] . '"></i> ' . $_language->module[ 'iemail' ] . '</a>';
        }
        $sem = '/[0-9]{4,11}/si';
        
        if ($loggedin && $ds[ 'userID' ] != $userID) {
            $pm = '<a class="label label-default" href="index.php?site=messenger&amp;action=touser&amp;touser=' . $ds[ 'userID' ] . '">
                <i class="fa fa-envelope" title="' . $_language->module[ 'message' ] . '"></i> ' . $_language->module['message'] . '
            </a>';
            
        } else {
            $pm = '';
        }

        if ($ds['homepage'] != '') {
            if (stristr($ds[ 'homepage' ], "https://")) {
                $homepage = '<a href="' . htmlspecialchars($ds[ 'homepage' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'homepage' ]) . '</a>';//https
            } else {
                $homepage = '<a href="http://' . htmlspecialchars($ds[ 'homepage' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'homepage' ]) . '</a>';//http
            }
        } else {
            $homepage = $_language->module[ 'n_a' ];
        }

        if ($ds[ 'twitch' ] != '') {
            if (stristr($ds[ 'twitch' ], "https://")) {
                $twitch = '<a href="' . htmlspecialchars($ds[ 'twitch' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'twitch' ]) . '</a>';
            } else {
                $twitch = '<a href="http://' . htmlspecialchars($ds[ 'twitch' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'twitch' ]) . '</a>';
            }
        } else {
            $twitch = $_language->module[ 'n_a' ];
        }

        if ($ds[ 'youtube' ] != '') {
            if (stristr($ds[ 'youtube' ], "https://")) {
                $youtube = '<a href="' . htmlspecialchars($ds[ 'youtube' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'youtube' ]) . '</a>';
            } else {
                $youtube = '<a href="http://' . htmlspecialchars($ds[ 'youtube' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'youtube' ]) . '</a>';
            }
        } else {
            $youtube = $_language->module[ 'n_a' ];
        }

        if ($ds[ 'twitter' ] != '') {
            if (stristr($ds[ 'twitter' ], "https://")) {
                $twitter = '<a href="' . htmlspecialchars($ds[ 'twitter' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'twitter' ]) . '</a>';
            } else {
                $twitter = '<a href="http://' . htmlspecialchars($ds[ 'twitter' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'twitter' ]) . '</a>';
            }
        } else {
            $twitter = $_language->module[ 'n_a' ];
        }

        if ($ds[ 'instagram' ] != '') {
            if (stristr($ds[ 'instagram' ], "https://")) {
                $instagram = '<a href="' . htmlspecialchars($ds[ 'instagram' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'instagram' ]) . '</a>';
            } else {
                $instagram = '<a href="http://' . htmlspecialchars($ds[ 'instagram' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'instagram' ]) . '
                </a>';
            }
        } else {
            $instagram = $_language->module[ 'n_a' ];
        }

        if ($ds[ 'facebook' ] != '') {
            if (stristr($ds[ 'facebook' ], "https://")) {
                $facebook = '<a href="' . htmlspecialchars($ds[ 'facebook' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'facebook' ]) . '</a>';
            } else {
                $facebook = '<a href="http://' . htmlspecialchars($ds[ 'facebook' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'facebook' ]) . '</a>';
            }
        } else {
            $facebook = $_language->module[ 'n_a' ];
        }

        if ($ds[ 'steam' ] != '') {
            if (stristr($ds[ 'steam' ], "https://")) {
                $steam = '<a href="' . htmlspecialchars($ds[ 'steam' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'steam' ]) . '</a>';
            } else {
                $steam = '<a href="http://' . htmlspecialchars($ds[ 'steam' ]) . '" target="_blank" rel="nofollow">' . htmlspecialchars($ds[ 'steam' ]) . '</a>';
            }
        } else {
            $steam = $_language->module[ 'n_a' ];
        }
        
        $firstname = $ds[ 'firstname' ];
        if ($firstname == '') {
            $firstname = $_language->module[ 'n_a' ];
        }

        $lastname = $ds[ 'lastname' ];
        if ($lastname == '') {
            $lastname = "";
        }

        $birthday = getformatdate(strtotime($ds['birthday']));
        if ($birthday == '30.11.-0001') {
            $birthday = $_language->module[ 'n_a' ];
        }

        if ($lastlogin == '01.01.1970') {
            $lastlogin = $_language->module[ 'n_a' ];
        }

        $res =
            safe_query(
                "SELECT
                    birthday,
                    DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(birthday)), '%Y') 'age'
                FROM
                    " . PREFIX . "user
                WHERE
                    userID = '" . (int)$id."'"
            );
        $cur = mysqli_fetch_array($res);
        
        $birthday = $birthday . " (" . (int)$cur[ 'age' ] . " " . $_language->module[ 'years' ] . ")";

        if ($ds[ 'sex' ] == "f") {
            $sex = $_language->module[ 'female' ];
        } elseif ($ds[ 'sex' ] == "m") {
            $sex = $_language->module[ 'male' ];
        } else {
            $sex = $_language->module[ 'unknown' ];
        }
        
                
        $town = $ds[ 'town' ];
        if ($town == '') {
            $town = $_language->module[ 'n_a' ];
        }

        if ($ds[ 'about' ]) {
            $about = $ds[ 'about' ];

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($about);
            $about = $translate->getTextByLanguage($about);
            
        } else {
            $about = $_language->module[ 'n_a' ];
        }

        $data_array = array();
		$data_array['$id'] = $id;
        $data_array['$userpic'] = $userpic;
		$data_array['$profile_bg'] = $profile_bg;
        $data_array['$nickname'] = $nickname;
        $data_array['$member'] = $member;
        $data_array['$firstname'] = $firstname;
        $data_array['$lastname'] = $lastname;
        $data_array['$sex'] = $sex;
        $data_array['$birthday'] = $birthday;
        $data_array['$town'] = $town;
        $data_array['$status'] = $status;
        $data_array['$registered'] = $registered;
        $data_array['$lastlogin'] = $lastlogin;
        $data_array['$email'] = $email;
        $data_array['$pm'] = $pm;
        $data_array['$homepage'] = $homepage;
        $data_array['$twitch'] = $twitch;
        $data_array['$youtube'] = $youtube;
        $data_array['$twitter'] = $twitter;
        $data_array['$instagram'] = $instagram;
        $data_array['$facebook'] = $facebook;
        $data_array['$steam'] = $steam;
        $data_array['$about'] = $about;
        

        $data_array['$personal_info'] = $_language->module[ 'personal_info' ];
        $data_array['$real_name'] = $_language->module[ 'real_name' ];
        $data_array['$nick_name'] = $_language->module[ 'nickname' ];
        $data_array['$age'] = $_language->module[ 'age' ];
        $data_array['$sexuality'] = $_language->module[ 'sexuality' ];
        $data_array['$location'] = $_language->module[ 'location' ];
		$data_array['$status_on_off'] = $_language->module[ 'status' ];
        $data_array['$last_login'] = $_language->module[ 'last_login' ];
        $data_array['$usertitle'] = $_language->module[ 'usertitle' ];
        $data_array['$home_page'] = $_language->module[ 'homepage' ];
        $data_array['$contact'] = $_language->module[ 'contact' ];
        $data_array['$message'] = $_language->module[ 'message' ];
        $data_array['$iemail'] = $_language->module[ 'iemail' ];
		$data_array['$social_media'] = $_language->module[ 'social-media' ];
        $data_array['$media_twitch'] = $_language->module[ 'twitch' ];
        $data_array['$media_youtube'] = $_language->module[ 'youtube' ];
        $data_array['$media_twitter'] = $_language->module[ 'twitter' ];
        $data_array['$media_instagram'] = $_language->module[ 'instagram' ];
        $data_array['$media_facebook'] = $_language->module[ 'facebook' ];
        $data_array['$media_steam'] = $_language->module[ 'steam' ];
        $data_array['$media_about'] = $_language->module[ 'about' ];
        
        $template = $tpl->loadTemplate("profile","content", $data_array);
        echo $template;
    
} else {
    redirect('index.php', $_language->module[ 'user_doesnt_exist' ], 3);
}