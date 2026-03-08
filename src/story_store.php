<?php
require_once './lib/config.php';
require_once './lib/global.php';
require_once './lib/session.php';

startSession();

try {
    // Initialize form data array
    $data = [];
    // Initialize errors array
    $errors = [];

    // Check if request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    // Extract form data into an array
    $data = [
        'headline'    => $_POST['headline'] ?? '',
        'article'     => $_POST['article'] ?? '',
        'category_id' => $_POST['category_id'] ?? '',
        // TODO: Add the rest of your form fields here
    ];

    // Validate the data
    $rules = [
        'headline'    => 'required|notempty|min:5|max:255',
        'article'     => 'required|notempty|min:20',
        'category_id' => 'required|integer',
        // TODO: Add validation rules for the rest of your fields
    ];

    $validator = new Validator($data, $rules);
    if ($validator->fails()) {
        foreach ($validator->errors() as $field => $fieldErrors) {
            $errors[$field] = $fieldErrors[0];
        }
        throw new Exception('Validation failed.');
    }

    // Save the story
    $story = new Story();
    $story->headline = $data['headline'];
    $story->category_id = $data['category_id'];
    $story->article = $data['article'];
    
    // TODO: Set the rest of the story properties from $data
    // For now, these are set to placeholder values for the additional fields
    $story->short_headline = 'Sample short headline';
    $story->subheadline = 'Sample subheadline';
    $story->author_id = 1;
    $story->location_id = 1;
    $story->img_url = 'images/placeholder.jpg';
    
    $story->save();

    // Clear any old form data
    clearFormData();
    // Clear any old errors
    clearFormErrors();

    // Set success flash message
    setFlashMessage('success', 'Story created successfully!');
    
    // Redirect to the view page for the newly created story
    redirect('view_story.php?id=' . $story->id);

} catch (Exception $e) {
    // Store errors and form data in the session so the form can display them
    setFormErrors($errors);
    setFormData($data);
    setFlashMessage('error', 'Error: ' . $e->getMessage());

    // Redirect back to the form page
    redirect('story_create.php');
}
