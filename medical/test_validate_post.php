<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_validate_post.php,v 1.1 2004/02/19 18:36:38 jact Exp $
 */

/**
 * test_validate_post.php
 ********************************************************************
 * Validate post data of a medical problem test
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 19/02/04 19:36
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/file_lib.php");

  $test->setIdProblem($_POST["id_problem"]);
  $_POST["id_problem"] = $test->getIdProblem();

  $test->setDocumentType($_POST["document_type"]);
  $_POST["document_type"] = $test->getDocumentType();

  $aux = trim(($_POST["upload_file"] != "") ? $_POST["upload_file"]: $_POST["previous"]);
  if ($aux != $_POST["previous"])
  {
    // upload file
    if (trim($_FILES['path_filename']['name']))
    {
      if (uploadFile($_FILES['path_filename'], dirname(realpath(__FILE__)) . '/../tests'))
      {
        $test->setPathFilename('../tests/' . $_FILES['path_filename']['name']);
      }
    }
  }
  else
  {
    if ($aux)
    {
      $aux = str_replace("\\", "/", $aux);
      $aux = ereg_replace("[/]+", "/", $aux);
      $test->setPathFilename($aux);
    }
  }
  $_POST["upload_file"] = $test->getPathFilename();

  if ( !$test->validateData() )
  {
    $pageErrors["path_filename"] = $test->getPathFilenameError();

    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;

    header("Location: " . $errorLocation);
    exit();
  }
?>