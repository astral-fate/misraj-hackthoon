import sys
import pyttsx3

def text_to_speech(text_file, mp3_file):
    # Initialize text-to-speech engine
    engine = pyttsx3.init()

    # Read text from the file
    with open(text_file, 'r', encoding='utf-8') as file:
        text = file.read()

    # Save the speech to an MP3 file
    engine.save_to_file(text, mp3_file)
    engine.runAndWait()

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python pdf_to_mp3.py <input_text_file> <output_mp3_file>")
        sys.exit(1)

    input_text_file = sys.argv[1]
    output_mp3_file = sys.argv[2]

    text_to_speech(input_text_file, output_mp3_file)
