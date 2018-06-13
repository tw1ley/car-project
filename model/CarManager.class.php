<?php

# ======================================================================================================= #

namespace App\M;

class CarManager implements \App\I\IGet
{
    public const CAR_TABLE = 'cars';
    public const CAR_RESERVATION_TABLE = 'cars_reservation';

    public function getOne($url) {
        return dbRow("SELECT `id`, `url`, `title`, `description`, `name`, `content`, `foto` FROM `".self::CAR_TABLE."` WHERE `url` = ?", array($url));
    }

    public function getAll() {
        return dbArray("SELECT `id`, `url`, `title`, `description`, `name`, `content`, `foto` FROM `".self::CAR_TABLE."` ORDER BY `id` DESC");
    }

    public function getReservations($carID, $userID = null) {
        $query = "SELECT u.`id` as `uid`, u.`name` as `uname`, c.`id` as `rid`, c.`date_from`, c.`date_to`, IF(DATE(NOW()) < c.`date_from`, 1, 0) as `deletable` ".
                 "FROM `".self::CAR_RESERVATION_TABLE."` c ".
                 "JOIN `".\App\M\UserManager::USER_TABLE."` u ON c.`id_user` = u.`id` ".
                 "WHERE DATE(NOW()) <= c.`date_to` AND c.`id_car` = ? ".
                 "ORDER BY c.`date_from`";
        $parms = array($carID);

        if ($userID) {
            $query .= " AND c.`id_user` = ?";
            $parms[] = $userID;
        }

        return dbArray($query, $parms);
    }

    public function setReservation($carID, $userID, $dateFrom, $dateTo) {
        dbQuery("INSERT INTO `".self::CAR_RESERVATION_TABLE."` (`id_car`, `id_user`, `date_from`, `date_to`) VALUES (:id_car, :id_user, :date_from, :date_to)", array(
            'id_car' => $carID,
            'id_user' => $userID,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ));
    }

    public function removeReservation($resID, $userID) {
        if (dbOne("SELECT c.`id` FROM `".self::CAR_RESERVATION_TABLE."` c WHERE DATE(NOW()) < c.`date_from` AND c.`id` = ? AND c.`id_user` = ?", array($resID, $userID))) {
            return dbQuery("DELETE FROM `".self::CAR_RESERVATION_TABLE."` WHERE `id` = :id_res AND `id_user` = :id_user", array(
                'id_res' => $resID,
                'id_user' => $userID
            ));
        }
        return 0;
    }

    public function reserve($carID, $userID, $dateFrom, $dateTo) {
        $dateFrom = new \App\M\Date($dateFrom);
        $dateTo = new \App\M\Date($dateTo);

        if ($carID && $userID && !$dateFrom->isNull() && !$dateTo->isNull()) {
            if ($dateFrom->isAtLeastToday() && $dateFrom->isAtLeastEqual($dateTo)) {
                $valid = true;

                $res = $this->getReservations($carID);
                if ($res) {
                    foreach ($res as $dates) {
                        $range = new \App\M\DateRange(new \App\M\Date($dates['date_from']), new \App\M\Date($dates['date_to']));
                        if (!$range->compareRange(new \App\M\DateRange($dateFrom, $dateTo))) {
                            $valid = false;
                            break;
                        }
                    }
                }

                if ($valid) {
                    $this->setReservation($carID, $userID, $dateFrom->date, $dateTo->date);
                }
            }
        }

        return false;
    }
}
