<?php

class User
{
  public function __construct($user_id)
  {
    $this->id = $user_id;
  }

  public function get_user()
  {
    try {
      $dbh = db_connect();
      $sql = "SELECT *
    FROM user
    WHERE id = :id";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':id' => $this->id));
      return $stmt->fetch();
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      set_flash('error', ERR_MSG1);
    }
  }

  public function get_users($type)
  {
    try {
      $dbh = db_connect();

      switch ($type) {
        case 'all':
          $sql = "SELECT *
              FROM user
              ORDER BY id DESC";
          $stmt = $dbh->prepare($sql);
          break;

        case 'match':
          $sql = "SELECT *
                FROM user INNER JOIN `match` ON user.id = match.match_user_id
          WHERE match.user_id = :user_id and match.unmatch_flg = 0";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':user_id', $this->id);
          break;

        case 'follows':
          $sql = "SELECT *
          FROM user INNER JOIN relation ON user.id = relation.follower_id
          WHERE relation.follow_id = :follow_id";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':follow_id', $this->id);
          break;

        case 'followers':
          $sql = "SELECT *
          FROM user INNER JOIN relation ON user.id = relation.follow_id
          WHERE relation.follower_id = :follower_id";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':follower_id', $this->id);
          break;
      }
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      _debug('複数ユーザー取得失敗');
    }
  }

  public function get_user_count($object)
  {
    try {
      $dbh = db_connect();
      switch ($object) {

        case 'favorite':
          $sql = "SELECT COUNT(post_id)
          FROM favorite
          WHERE user_id = :id";
          break;

        case 'post':
          $sql = "SELECT COUNT(id)
            FROM post
            WHERE user_id = :id";
          break;

        case 'comment':
          $sql = "SELECT COUNT(id)
            FROM comment
            WHERE user_id = :id";
          break;

        case 'follow':
          $sql = "SELECT COUNT(follower_id)
          FROM relation
          WHERE follow_id = :id";
          break;

        case 'follower':
          $sql = "SELECT COUNT(follow_id)
          FROM relation
          WHERE follower_id = :id";
          break;

        case 'message':
          $sql = "SELECT COUNT(id)
          FROM message
          WHERE user_id = :id";
          break;

        case 'message_relation':
          $sql = "SELECT COUNT(id)
          FROM message_relation
          WHERE user_id = :id";
          break;
      }
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':id' => $this->id));
      return $stmt->fetch();
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      _debug('ユーザー数取得失敗');
    }
  }

  function get_newuser($name, $password)
  {
    try {
      $dbh = db_connect();
      $sql = "SELECT id,name,password,profile,image
              FROM user
              WHERE name = :name and password = :password";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':name' => $name, ':password' => $password));
      return $stmt->fetch();
    } catch (\Exception $e) {
      _debug('新規ユーザー取得失敗');
    }
  }

  function update_login_time($date)
  {
    try {
      $dbh = db_connect();
      $dbh->beginTransaction();
      $sql = 'UPDATE user SET login_time = :date WHERE id = :id';
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':date' => $date->format('Y-m-d H:i:s'), ':id' => $this->id));
      $dbh->commit();
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      _debug('ログイン時刻更新失敗');
      $dbh->rollback();
      reload();
    }
  }

  //  フォロー中かどうか確認する
  function check_follow($follow_user, $follower_user)
  {
    try {
      $dbh = db_connect();
      $sql = "SELECT follow_id,follower_id
          FROM relation
          WHERE :follower_id = follower_id AND :follow_id = follow_id";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(
        ':follow_id' => $follow_user,
        ':follower_id' => $follower_user
      ));
      return  $stmt->fetch();
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      _debug('フォロー確認失敗');
    }
  }

  function check_user($user_name)
  {
    try {
      $dbh = db_connect();
      $sql = "SELECT name
          FROM user
          WHERE :name = name";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':name' => $user_name));
      return  $stmt->fetch();
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      _debug('ユーザー確認失敗');
    }
  }
}

class Memo
{
  public function __construct($memo_id)
  {
    $this->id = $memo_id;
  }

  function get_memo()
  {
    try {
      $dbh = db_connect();
      $sql = "SELECT *
            FROM memo
            WHERE id = :id";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':id' => $this->id));
      return $stmt->fetch();
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      _debug('投稿取得失敗');
    }
  }
  //　お気に入りの投稿数を取得する
  function get_memo_favorite_count()
  {
    try {
      $dbh = db_connect();
      $sql = "SELECT COUNT(user_id)
          FROM favorite
          WHERE memo_id = :memo_id";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':memo_id' => $this->id));
      return $stmt->fetch();
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      _debug('お気に入り投稿数取得失敗');
    }
  }

  //　投稿IDからコメントを取得する
  function get_memo_comment_count()
  {
    try {
      $dbh = db_connect();
      $sql = "SELECT COUNT(id)
          FROM comment
          WHERE memo_id = :memo_id";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':memo_id' => $this->id));
      return $stmt->fetch();
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      _debug('投稿からコメント取得失敗');
    }
  }

  //　投稿を複数取得する
  function get_memos($user_id, $type, $query)
  {
    try {
      $dbh = db_connect();
      switch ($type) {
        case 'all':
          $sql = "SELECT *
              FROM memo
              ORDER BY created_at DESC";
          $stmt = $dbh->prepare($sql);
          break;
          //　自分の投稿を取得する
        case 'mymemo':
          $sql = "SELECT memo.id,memo.text,memo.image,memo.user_id,memo.created_at,favorite.memo_id
          FROM memo LEFT OUTER JOIN favorite ON memo.id = favorite.memo_id
          INNER JOIN user ON user.id = memo.user_id
          WHERE memo.user_id = :id
          order by favorite.memo_id,memo.created_at DESC";
          _debug($user_id);
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':id', $user_id);
          break;
          //　フォローしているユーザーの投稿を取得する
        case 'follow':
          $sql = "SELECT *
                FROM memo INNER JOIN relation ON memo.user_id = relation.follower_id
                WHERE relation.follow_id = :id
                ORDER BY created_at DESC";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':id', $user_id);
          break;
          //　検索結果の投稿を取得する
        case 'search':
          $sql = "SELECT *
                FROM memo
                WHERE text LIKE CONCAT('%',:input,'%')
                ORDER BY id DESC";
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(':input', $query);
          break;
      }
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (\Exception $e) {
      error_log($e, 3, "../../php/error.log");
      _debug('複数の投稿取得失敗');
    }
  }
}