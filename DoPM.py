import os
from datetime import datetime

# Definer rotkatalogen for skanning
root_dir = os.path.dirname(os.path.abspath(__file__))  # Kan endres til ønsket katalog

# Få gjeldende dato og tid
date_time = datetime.now().strftime('%Y-%m-%d_%H-%M')

# Definer utdatafilen med gjeldende dato og tid
output_file = os.path.join(root_dir, f"overview_{date_time}.txt")

# Filtyper som skal inkluderes i skanningen
allowed_extensions = ['php', 'html', 'htm', 'js', 'css', 'py', 'sh', 'bash', 'htaccess', 'bashrc', 'bash_profile', 'Makefile']

# Maksimal filstørrelse (1MB i byte)
max_file_size = 1024 * 1024  # 1MB

def build_directory_map(dir_path, level=0):
    directory_map = ''
    indent = '  ' * level

    # Skann gjennom filer og mapper
    for item in os.listdir(dir_path):
        item_path = os.path.join(dir_path, item)

        # Unngå skjulte filer og mapper
        if item.startswith('.'):
            continue

        if os.path.isdir(item_path):
            # Gå rekursivt gjennom underkataloger
            directory_map += f"{indent}{os.path.basename(item_path)}\n"
            directory_map += build_directory_map(item_path, level + 1)
        else:
            # Få filtype og filstørrelse
            ext = os.path.splitext(item_path)[1][1:]  # Hent ut filtypen uten punktum
            file_size = os.path.getsize(item_path)

            # Sjekk om filtypen er tillatt og filstørrelsen er mindre enn eller lik 1 MB
            if ext in allowed_extensions and file_size <= max_file_size:
                directory_map += f"{indent}- {item} (Size: {round(file_size / 1024, 2)} KB)\n"

    return directory_map

def append_file_contents(dir_path, file_handle):
    # Skann gjennom filer og mapper
    for item in os.listdir(dir_path):
        item_path = os.path.join(dir_path, item)

        # Unngå skjulte filer og mapper
        if item.startswith('.'):
            continue

        if os.path.isdir(item_path):
            # Gå rekursivt gjennom underkataloger
            append_file_contents(item_path, file_handle)
        else:
            # Få filtype og filstørrelse
            ext = os.path.splitext(item_path)[1][1:]  # Hent ut filtypen uten punktum
            file_size = os.path.getsize(item_path)

            # Sjekk om filtypen er tillatt og filstørrelsen er mindre enn eller lik 1 MB
            if ext in allowed_extensions and file_size <= max_file_size:
                # Skriv filinnholdet til output filen
                file_handle.write(f"\n--- {item_path} ---\n\n")
                with open(item_path, 'r', encoding='utf-8', errors='ignore') as f:
                    file_handle.write(f.read() + "\n\n")

# Start skanningsprosessen
with open(output_file, 'w') as f:
    f.write("Directory Structure Map:\n\n")
    f.write(build_directory_map(root_dir))

    # Legg til filinnholdene etter kartet er skrevet
    append_file_contents(root_dir, f)

print(f"Directory scan complete. Overview written to {output_file}")
