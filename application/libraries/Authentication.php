<?php 
    class Authentication 
    {
        public function isAuthorizeAdmin($currentUserLevel)
        {
            if($currentUserLevel == 'admin')
                {return true;}
            else
                {return false;}
            
        }

        public function isAuthorizeDoctor($currentUserLevel)
        {
            if($currentUserLevel == 'doctor' || $currentUserLevel == 'super_admin' )
            {return true;}
            else
            {return false;}

        }

        public function isAuthorizePatient($currentUserLevel)
        {
            if($currentUserLevel == 'patient' || $currentUserLevel == 'super_admin' )
            {return true;}
            else
            {return false;}

        }

        public function isAuthorizeSuperAdmin($currentUserLevel)
        {
            if($currentUserLevel == 'super_admin' )            
                {return true;}
            else
                {return false;}
        }
    }
?>