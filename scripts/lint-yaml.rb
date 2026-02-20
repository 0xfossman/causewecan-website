#!/usr/bin/env ruby
# frozen_string_literal: true

require 'yaml'

files = Dir.glob('**/*.{yml,yaml}', File::FNM_DOTMATCH)
           .reject { |f| f.start_with?('.git/') }
           .sort

if files.empty?
  puts 'No YAML files found.'
  exit 0
end

errors = []

files.each do |file|
  begin
    YAML.load_file(file)
    puts "OK: #{file}"
  rescue StandardError => e
    errors << "#{file}: #{e.message}"
  end
end

if errors.any?
  warn 'YAML lint failed:'
  errors.each { |err| warn "- #{err}" }
  exit 1
end

puts "YAML lint passed (#{files.length} files)."
