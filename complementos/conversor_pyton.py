import os
from pdf2image import convert_from_path
from PIL import Image
import subprocess

# Caminhos dos diretórios
pdf_dir = r"C:\Users\DocMark\Documents\MATRICULAS-PDF"
tiff_dir = r"C:\xampp\htdocs\docmark\pdf-para-tiff\historico"
converted_dir = r"C:\xampp\htdocs\docmark\pdf-para-tiff\pdf-viw"

# Caminho do Poppler
poppler_path = r"C:\Program Files\poppler-24.08.0\Library\bin"

# Função para garantir que o nome tenha 8 caracteres numéricos
def normalizar_nome(nome_arquivo):
    nome_sem_extensao = os.path.splitext(nome_arquivo)[0]
    numero = ''.join(filter(str.isdigit, nome_sem_extensao))
    numero_normalizado = numero.zfill(8)
    return f"{numero_normalizado}.tiff"

# Função para normalizar o nome do arquivo PDF para 8 caracteres
def normalizar_nome_pdf(nome_arquivo):
    nome_sem_extensao = os.path.splitext(nome_arquivo)[0]
    numero = ''.join(filter(str.isdigit, nome_sem_extensao))
    numero_normalizado = numero.zfill(8)
    return f"{numero_normalizado}.pdf"

# Verificar se os diretórios de saída existem, se não, criar
os.makedirs(tiff_dir, exist_ok=True)
os.makedirs(converted_dir, exist_ok=True)

# Loop para converter todos os arquivos PDF para TIFF
for filename in os.listdir(pdf_dir):
    if filename.endswith('.pdf'):
        pdf_path = os.path.join(pdf_dir, filename)
        
        # Normalizar o nome do arquivo TIFF
        tiff_filename = normalizar_nome(filename)
        tiff_path = os.path.join(tiff_dir, tiff_filename)
        
        # Normalizar o nome do arquivo PDF antes de movê-lo
        pdf_normalizado = normalizar_nome_pdf(filename)
        pdf_new_path = os.path.join(converted_dir, pdf_normalizado)
        
        try:
            # Converter o PDF para imagens (com 200 DPI, todas as páginas)
            images = convert_from_path(pdf_path, dpi=200, poppler_path=poppler_path)
            
            # Converter cada página para 1-bit (preto e branco)
            images_1bit = [image.convert('1') for image in images]
            
            # Salvar a primeira página como TIFF com compressão Group 4
            images_1bit[0].save(tiff_path, save_all=True, append_images=images_1bit[1:], compression="group4")
            
            # Mover o PDF para o diretório de convertidos com o nome normalizado (substituindo se já existir)
            os.replace(pdf_path, pdf_new_path)  # Substitui o arquivo se já existir
            
            print(f"Arquivo convertido com sucesso: {pdf_path} -> {tiff_path}")
        
        except Exception as e:
            print(f"Erro ao converter o arquivo {pdf_path}: {e}")

# Executar os arquivos .bat após a conversão
try:
    # Executar o primeiro .bat
    subprocess.run([r"C:\xampp\htdocs\docmark\pdf-para-tiff\sincronizar.bat"], check=True)
    print("sincronizar.bat executado com sucesso.")
    
    # Executar o segundo .bat
    subprocess.run([r"C:\xampp\htdocs\docmark\pdf-para-tiff\sincronizar-indicador.bat"], check=True)
    print("sincronizar-indicador.bat executado com sucesso.")
    
except subprocess.CalledProcessError as e:
    print(f"Erro ao executar o arquivo .bat: {e}")
