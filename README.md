
# Basir - بصير


## Overview

**بصير** (Basir) is an inclusive educational platform designed to provide visually impaired students with comprehensive tools to facilitate their learning experience. The platform includes features such as note-taking, book conversion, file conversion, campus navigation, and a simplified blackboard interface, all tailored to meet the needs of visually impaired users.

## Features


1. **Convert Books**: Users can upload books in PDF or image format, and the platform will convert them into printable format PDFs with Braille.

2. **Campus Guide**: Using computer vision, the platform assists users in navigating the campus, providing auditory guidance and information about the surroundings.

3. **Simplified Blackboard**: Integrates with popular learning platforms to extract essential information, making it easier for visually impaired users to stay updated with their academic progress.




## How It Works

### Convert Files & Books to Braille and audiobook
Convert various files, including PDFs and images, into printable format PDFs with Braille format, making them accessible for visually impaired users. We have implemneted a native soultion without the need for extran APIs, for effeinacy concers.

* Smalot\PdfParser library to extract text from PDF 


* Python pyttsx3(text to speech) library convert to audiobooks

* Composer & Tesseract for optical charecter recognition OCR to convert images to text


### Campus Guide

Provides computer vision assistance for navigating campus surroundings. This feature helps students find their way around halls and read the names of staff members in each hall. We implemneted this using light-weight YOLO(you only look one) model, and pyttsx3 python library to read the detection out loud.



### Simplified Blackboard
Offers third-party access to learning platforms, allowing users to export important data such as grades and lecture links. Users can share the results via email or WhatsApp. We implemneted a demo of how the of fetching data from Blackboard or any SIS univerity system, and displaying them to visually imparied student in an accessible format.




## Tools used

* XAAMP as PHP-server side 

* Python pyttsx3(text to speech) library to convert text to speech

* Smalot\PdfParser library to extract text from PDF 

* Composer & Tesseract for optical charecter recognition OCR

* YOLO3-tiny for computer vision



## Getting Started

To run the project, make sure the dir is inside hdoc, then navigate to the url

 ` http://localhost:8000/  `

The home Page

![IMAGE_DESCRIPTION](https://github.com/astral-fate/misraj-hackthoon/assets/63984422/302232df-02e9-41e0-a960-05038c099635)

For running the computer vision model, open a new terminal and run 

 `python app.py `

Then the CV model will run smoothly and speak the detection out loud

![IMAGE_DESCRIPTION](https://github.com/astral-fate/yolo8-webcam/assets/63984422/fd2ad467-362b-47a2-963f-cee99a9a8e81)

![IMAGE_DESCRIPTION](https://github.com/astral-fate/yolo8-webcam/assets/63984422/15e5ab60-eef7-493c-87ab-11b80519fa5a)

![IMAGE_DESCRIPTION](https://github.com/astral-fate/yolo8-webcam/assets/63984422/ce19c18c-8810-4ef4-9328-e111944ad944)

![IMAGE_DESCRIPTION](https://github.com/astral-fate/yolo8-webcam/assets/63984422/5d6500e6-e07e-4279-b1b1-8188caa7f815)


The books can be converted to either Braille or audiobook. Then we compared the translation between braille<>English for quality assurance purposes.

![IMAGE_DESCRIPTION](https://github.com/astral-fate/misraj-hackthoon/assets/63984422/b0ee7ac7-c17f-48e1-9ff2-82abe14c15b6)



## Resources & refrences

1. https://github.com/UB-Mannheim/tesseract/wiki

2. https://github.com/MuhammadMoinFaisal/Computervisionprojects/blob/main/YOLOv8-CrashCourse/Running_YOLOv8_Webcam/YOLOv8_Webcam.py

3. https://github.com/smarthomefans/darknet-test/tree/master for downloading weights and cong for yolo3-tiny

4. https://www.codexworld.com/extract-text-from-pdf-using-php/

5. https://abcbraille.com/braille
