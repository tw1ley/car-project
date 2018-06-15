<?php

# ======================================================================================================= #

namespace App\M;

class CarManager implements \App\I\IGet
{
    public const CAR_TABLE = 'cars';
    public const CAR_RESERVATION_TABLE = 'cars_reservation';

    /**
     * Get specified car
     *
     */
    public function getOne($url) {
        $result = dbRow("SELECT `id`, `url`, `title`, `description`, `name`, `content`, `foto` FROM `".self::CAR_TABLE."` WHERE `url` = ?", array($url));
        if ($result) {
            $catalog = getConfig('files', self::CAR_TABLE, 'image', 'foto', 'dir');
            if ($catalog && !empty($result['foto'])) {
                $result['foto'] = 'files/image/'.$catalog.DIR_SEP.$result['foto'];
            }
        }
        return $result;
    }

    /**
     * Get all cars
     *
     */
    public function getAll() {
        $results = dbArray("SELECT `id`, `url`, `title`, `description`, `name`, `content`, `foto` FROM `".self::CAR_TABLE."` ORDER BY `id` DESC");
        if ($results) {
            $catalog = getConfig('files', self::CAR_TABLE, 'image', 'foto', 'dir');
            if ($catalog) {
                foreach ($results as $key => $result) {
                    if (!empty($result['foto'])) {
                        $results[$key]['foto'] = 'files/image/'.$catalog.DIR_SEP.$result['foto'];
                    }
                }
            }
        }
        return $results;
    }

    /**
     * Method to get car reservation information
     *
     */
    public function getReservations($carID, $userID = null, $deletable = false) {
        $select  = "SELECT u.`id` as `uid`, u.`name` as `uname`, c.`id` as `rid`, c.`date_from`, c.`date_to`";
        $from    = " FROM `".self::CAR_RESERVATION_TABLE."` c";
        $join    = " JOIN `".\App\M\UserManager::USER_TABLE."` u ON c.`id_user` = u.`id`";
        $where   = " WHERE DATE(NOW()) <= c.`date_to` AND c.`id_car` = ?";
        $orderBy = " ORDER BY c.`date_from`";

        $parms = array($carID);

        # === #

        if ($userID) {
            $where .= " AND c.`id_user` = ?";
            $parms[] = $userID;
        }

        if ($deletable) {
            $select .= ", IF(DATE(NOW()) < c.`date_from`, 1, 0) as `deletable`";
        }

        return dbArray($select.$from.$join.$where.$orderBy, $parms);
    }

    /**
     * Method to set car reservation
     *
     */
    public function setReservation($carID, $userID, $dateFrom, $dateTo) {
        $dateFrom = new \App\M\Date($dateFrom);
        $dateTo = new \App\M\Date($dateTo);

        if ($carID && $userID && !$dateFrom->isNull() && !$dateTo->isNull()) {
            if ($dateFrom->isAtLeastToday() && $dateFrom->isAtLeastEqual($dateTo)) {
                $valid = true;

                if ($res = $this->getReservations($carID)) {
                    foreach ($res as $dates) {
                        $range = new \App\M\DateRange(new \App\M\Date($dates['date_from']), new \App\M\Date($dates['date_to']));
                        if (!$range->compareRange(new \App\M\DateRange($dateFrom, $dateTo))) {
                            $valid = false;
                            break;
                        }
                    }
                }

                if ($valid) {
                    return dbQuery("INSERT INTO `".self::CAR_RESERVATION_TABLE."` (`id_car`, `id_user`, `date_from`, `date_to`) VALUES (:id_car, :id_user, :date_from, :date_to)", array(
                        'id_car' => $carID,
                        'id_user' => $userID,
                        'date_from' => $dateFrom->date,
                        'date_to' => $dateTo->date
                    ));
                }
            }
        }

        return false;
    }

    /**
     * Method to delete car reservation
     *
     */
    public function removeReservation($resID, $userID) {
        if (dbOne("SELECT c.`id` FROM `".self::CAR_RESERVATION_TABLE."` c WHERE DATE(NOW()) < c.`date_from` AND c.`id` = ? AND c.`id_user` = ?", array($resID, $userID))) {
            return dbQuery("DELETE FROM `".self::CAR_RESERVATION_TABLE."` WHERE `id` = :id_res AND `id_user` = :id_user", array(
                'id_res' => $resID,
                'id_user' => $userID
            ));
        }
        return 0;
    }
}
